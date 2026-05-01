<?php

namespace App\Enums;

final class ConfigKey
{
    /** @var string 是否启用注册 */
    const IsEnableRegistration = 'is_enable_registration';

    /** @var string 是否启用画廊 */
    const IsEnableGallery = 'is_enable_gallery';

    /** @var string 是否启用接口 */
    const IsEnableApi = 'is_enable_api';

    /** @var string 程序名称 */
    const AppName = 'app_name';

    /** @var string 程序版本 */
    const AppVersion = 'app_version';

    /** @var string 站点关键字 */
    const SiteKeywords = 'site_keywords';

    /** @var string 站点描述 */
    const SiteDescription = 'site_description';

    /** @var string 站点公告 */
    const SiteNotice = 'site_notice';

    /** @var string icp备案号 */
    const IcpNo = 'icp_no';

    /** @var string 是否允许游客上传 */
    const IsAllowGuestUpload = 'is_allow_guest_upload';

    /** @var string 用户初始容量(kb) */
    const UserInitialCapacity = 'user_initial_capacity';

    /** @var string 账户是否需要验证 */
    const IsUserNeedVerify = 'is_user_need_verify';

    /** @var string 邮件配置 */
    const Mail = 'mail';

    /** @var string 角色组默认配置 */
    const Group = 'group';

    /** @var string 自定义 CSS */
    const CustomCss = 'custom_css';

    /** @var string 自定义 JS */
    const CustomJs = 'custom_js';

    /** @var string 是否启用 OAuth 登录 */
    const OauthEnable = 'oauth_enable';

    /** @var string 是否允许 OAuth 快速注册 */
    const OauthAllowRegister = 'oauth_allow_register';

    /** @var string OAuth 登录展示名称 */
    const OauthProviderName = 'oauth_provider_name';

    /** @var string OAuth 客户端 ID */
    const OauthClientId = 'oauth_client_id';

    /** @var string OAuth 客户端密钥 */
    const OauthClientSecret = 'oauth_client_secret';

    /** @var string OAuth 授权地址 */
    const OauthAuthorizeUrl = 'oauth_authorize_url';

    /** @var string OAuth Token 地址 */
    const OauthTokenUrl = 'oauth_token_url';

    /** @var string OAuth 用户信息地址 */
    const OauthUserinfoUrl = 'oauth_userinfo_url';

    /** @var string OAuth Scope */
    const OauthScope = 'oauth_scope';

    /** @var string OAuth 唯一标识字段 */
    const OauthUserIdField = 'oauth_user_id_field';

    /** @var string OAuth 用户名字段 */
    const OauthUserNameField = 'oauth_user_name_field';

    /** @var string OAuth 邮箱字段 */
    const OauthUserEmailField = 'oauth_user_email_field';

    /** @var string 是否启用 OAuth PKCE */
    const OauthPkceEnable = 'oauth_pkce_enable';
}
