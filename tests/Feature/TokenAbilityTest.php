<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Group;
use App\Models\Image;
use App\Models\Strategy;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class TokenAbilityTest extends TestCase
{
    use DatabaseMigrations;

    public function test_api_token_creation_defaults_to_all_abilities(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/v1/tokens', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.abilities', $this->allAbilities());

        $token = PersonalAccessToken::query()->where('tokenable_id', $user->id)->latest('id')->first();

        $this->assertNotNull($token);
        $this->assertSame($this->allAbilities(), $token->abilities);
    }

    public function test_api_token_creation_accepts_subset_abilities(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $abilities = ['images.upload', 'strategies.read'];

        $response = $this->postJson('/api/v1/tokens', [
            'email' => $user->email,
            'password' => 'password',
            'abilities' => $abilities,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.abilities', $abilities);

        $token = PersonalAccessToken::query()->where('tokenable_id', $user->id)->latest('id')->first();

        $this->assertNotNull($token);
        $this->assertSame($abilities, $token->abilities);
    }

    public function test_web_token_creation_defaults_to_all_abilities(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->postJson('/api/tokens', [
            'name' => 'browser token',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.abilities', $this->allAbilities());

        $token = $user->tokens()->latest('id')->first();

        $this->assertNotNull($token);
        $this->assertSame($this->allAbilities(), $token->abilities);
    }

    public function test_web_token_creation_accepts_subset_abilities(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $abilities = ['images.read', 'albums.read'];

        $response = $this->actingAs($user)->postJson('/api/tokens', [
            'name' => 'browser token',
            'password' => 'password',
            'abilities' => $abilities,
        ]);

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.abilities', $abilities);

        $token = $user->tokens()->latest('id')->first();

        $this->assertNotNull($token);
        $this->assertSame($abilities, $token->abilities);
    }

    public function test_token_index_returns_all_abilities_for_legacy_token(): void
    {
        $user = User::factory()->create();
        $plainTextToken = $user->createToken('legacy-index')->plainTextToken;
        [$id] = explode('|', $plainTextToken, 2);

        PersonalAccessToken::query()->whereKey($id)->update([
            'abilities' => null,
        ]);

        $response = $this->withToken($plainTextToken)->getJson('/api/v1/tokens');

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.tokens.0.abilities', $this->allAbilities());
    }

    public function test_updating_token_name_does_not_change_abilities(): void
    {
        $user = User::factory()->create();
        $token = $user->tokens()->create([
            'name' => 'before',
            'token' => hash('sha256', 'secret-token'),
            'abilities' => ['images.read'],
        ]);

        $response = $this->actingAs($user)->putJson("/api/tokens/{$token->id}", [
            'name' => 'after',
            'abilities' => ['images.delete', 'profile.read'],
        ]);

        $response->assertOk()->assertJsonPath('status', true);

        $token->refresh();

        $this->assertSame('after', $token->name);
        $this->assertSame(['images.read'], $token->abilities);
    }

    public function test_profile_endpoint_requires_profile_read_ability(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('limited', ['images.read'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/profile');

        $response->assertForbidden()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', config('token_abilities.forbidden_message'));
    }

    public function test_profile_endpoint_allows_profile_read_ability(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('profile', ['profile.read'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/profile');

        $response->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_images_endpoint_requires_images_read_ability(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('limited', ['profile.read'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/images');

        $response->assertForbidden()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', config('token_abilities.forbidden_message'));
    }

    public function test_images_endpoint_allows_legacy_token_without_abilities(): void
    {
        $user = User::factory()->create();
        $plainTextToken = $user->createToken('legacy-images')->plainTextToken;
        [$id] = explode('|', $plainTextToken, 2);

        PersonalAccessToken::query()->whereKey($id)->update([
            'abilities' => null,
        ]);

        $response = $this->withToken($plainTextToken)->getJson('/api/v1/images');

        $response->assertOk()->assertJsonPath('status', true);
    }

    public function test_album_endpoints_require_matching_abilities(): void
    {
        $user = User::factory()->create();
        $album = $this->createAlbumForUser($user);
        $readToken = $user->createToken('read-only', ['albums.read'])->plainTextToken;

        $this->withToken($readToken)
            ->getJson('/api/v1/albums')
            ->assertOk()
            ->assertJsonPath('status', true);

        $this->withToken($readToken)
            ->deleteJson("/api/v1/albums/{$album->id}")
            ->assertForbidden()
            ->assertJsonPath('message', config('token_abilities.forbidden_message'));

        $deleteToken = $user->createToken('delete-only', ['albums.delete'])->plainTextToken;

        $this->withToken($deleteToken)
            ->deleteJson("/api/v1/albums/{$album->id}")
            ->assertOk()
            ->assertJsonPath('status', true);

        $this->assertDatabaseMissing('albums', [
            'id' => $album->id,
        ]);
    }

    public function test_image_delete_endpoint_requires_delete_ability(): void
    {
        $user = User::factory()->create();
        $image = $this->createImageForUser($user);
        $token = $user->createToken('read-only', ['images.read'])->plainTextToken;

        $response = $this->withToken($token)->deleteJson("/api/v1/images/{$image->key}");

        $response->assertForbidden()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', config('token_abilities.forbidden_message'));

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
        ]);
    }

    public function test_image_delete_endpoint_allows_delete_ability(): void
    {
        $user = User::factory()->create();
        $image = $this->createImageForUser($user);
        $token = $user->createToken('delete-only', ['images.delete'])->plainTextToken;

        $response = $this->withToken($token)->deleteJson("/api/v1/images/{$image->key}");

        $response->assertOk()->assertJsonPath('status', true);

        $this->assertDatabaseMissing('images', [
            'id' => $image->id,
        ]);
    }

    public function test_guest_can_still_access_strategies_without_token(): void
    {
        $response = $this->getJson('/api/v1/strategies');

        $response->assertOk()
            ->assertJsonPath('status', true);

        $strategies = data_get($response->json(), 'data.strategies', []);

        $this->assertNotEmpty($strategies);
    }

    public function test_strategies_endpoint_requires_strategies_read_ability_for_token_requests(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('limited', ['profile.read'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/strategies');

        $response->assertForbidden()
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', config('token_abilities.forbidden_message'));
    }

    public function test_strategies_endpoint_allows_strategies_read_ability_for_token_requests(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('strategies', ['strategies.read'])->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/strategies');

        $response->assertOk()
            ->assertJsonPath('status', true);
    }

    protected function allAbilities(): array
    {
        return collect(config('token_abilities.groups'))
            ->pluck('abilities')
            ->flatMap(fn (array $abilities) => array_keys($abilities))
            ->values()
            ->all();
    }

    protected function createAlbumForUser(User $user): Album
    {
        return $user->albums()->create([
            'name' => 'Test album',
            'intro' => '',
            'image_num' => 0,
        ]);
    }

    protected function createImageForUser(User $user): Image
    {
        /** @var Group $group */
        $group = $user->group;
        /** @var Strategy $strategy */
        $strategy = $group->strategies()->firstOrFail();

        return Image::query()->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'strategy_id' => $strategy->id,
            'album_id' => null,
            'key' => 'image-key-'.uniqid(),
            'path' => '',
            'name' => 'test.png',
            'origin_name' => 'test.png',
            'alias_name' => '',
            'size' => 12,
            'mimetype' => 'image/png',
            'extension' => 'png',
            'md5' => md5('test-file'),
            'sha1' => sha1('test-file'),
            'width' => 1,
            'height' => 1,
            'permission' => 0,
            'is_unhealthy' => false,
            'uploaded_ip' => '127.0.0.1',
        ]);
    }
}
