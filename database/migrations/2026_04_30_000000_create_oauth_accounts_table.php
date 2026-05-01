<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('oauth_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider', 64)->comment('OAuth 提供方标识');
            $table->string('provider_user_id', 191)->comment('OAuth 用户唯一标识');
            $table->string('provider_user_name')->nullable()->comment('OAuth 用户名');
            $table->string('provider_user_email')->nullable()->comment('OAuth 邮箱');
            $table->text('access_token')->nullable()->comment('访问令牌');
            $table->text('refresh_token')->nullable()->comment('刷新令牌');
            $table->timestamp('token_expires_at')->nullable()->comment('令牌过期时间');
            $table->json('scopes')->nullable()->comment('授权范围');
            $table->json('raw_profile')->nullable()->comment('原始用户信息');
            $table->timestamps();

            $table->unique(['provider', 'provider_user_id']);
            $table->unique(['user_id', 'provider']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('oauth_accounts');
    }
};
