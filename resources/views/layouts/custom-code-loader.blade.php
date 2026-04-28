@php
    $customCodeLoaderType = $type ?? 'css';
    $customCss = \App\Utils::config(\App\Enums\ConfigKey::CustomCss, '');
    $customJs = \App\Utils::config(\App\Enums\ConfigKey::CustomJs, '');

    $customCss = is_string($customCss) ? $customCss : '';
    $customJs = is_string($customJs) ? $customJs : '';
@endphp

@if($customCodeLoaderType === 'css')
    <script>
        (() => {
            const endpoint = @json(route('custom.code', ['type' => 'css']));
            const cssHash = @json(md5($customCss));
            const ttl = 7 * 24 * 60 * 60 * 1000;
            const keys = {
                css: 'lsky:custom-css:v1',
                cssHash: 'lsky:custom-css-hash:v1',
                cssTime: 'lsky:custom-css-time:v1',
            };

            const now = Date.now();

            const get = (key) => {
                try {
                    return localStorage.getItem(key);
                } catch (error) {
                    return null;
                }
            };

            const set = (key, value) => {
                try {
                    localStorage.setItem(key, value);
                } catch (error) {
                    return false;
                }

                return true;
            };

            const remove = (...targetKeys) => {
                try {
                    targetKeys.forEach((key) => localStorage.removeItem(key));
                } catch (error) {
                    // noop
                }
            };

            const upsertStyle = (content) => {
                let style = document.getElementById('custom-css-runtime');
                if (!content) {
                    style?.remove();
                    return;
                }

                if (!style) {
                    style = document.createElement('style');
                    style.id = 'custom-css-runtime';
                    document.head.appendChild(style);
                }

                style.textContent = content;
            };

            const isFresh = () => {
                const cachedHash = get(keys.cssHash);
                const cachedTime = Number(get(keys.cssTime) || 0);

                if (!cssHash) {
                    return false;
                }

                return cachedHash === cssHash && cachedTime > 0 && now - cachedTime < ttl;
            };

            if (cssHash) {
                if (isFresh()) {
                    upsertStyle(get(keys.css));
                }
            } else {
                remove(keys.css, keys.cssHash, keys.cssTime);
                upsertStyle('');
            }

            if (isFresh()) {
                return;
            }

            fetch(endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            }).then(async (response) => {
                if (!response.ok) {
                    return null;
                }

                return response.json();
            }).then((payload) => {
                if (!payload) {
                    return;
                }

                const data = payload?.data || {};
                const remoteCss = typeof data.css === 'string' ? data.css : '';
                const remoteCssHash = typeof data.css_hash === 'string' ? data.css_hash : '';

                if (remoteCss && remoteCssHash) {
                    set(keys.css, remoteCss);
                    set(keys.cssHash, remoteCssHash);
                    set(keys.cssTime, String(Date.now()));
                    upsertStyle(remoteCss);
                } else {
                    remove(keys.css, keys.cssHash, keys.cssTime);
                    upsertStyle('');
                }
            }).catch(() => {
                // noop
            });
        })();
    </script>
@elseif($customJs !== '')
    <script>{!! $customJs !!}</script>
@endif
