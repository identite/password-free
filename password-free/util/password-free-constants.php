<?php

if (!defined('ABSPATH')) {
    exit;
}

global $wp;

define('PASSWORD_FREE_API_CURRENT_URL', get_site_url());
define('PASSWORD_FREE_PATH', plugin_dir_path(PASSWORD_FREE_FILE));
define('PASSWORD_FREE_URL', plugin_dir_url(PASSWORD_FREE_FILE));
define('PASSWORD_FREE_PLUGINS_PAGE_URL', admin_url() . 'plugins.php');
define('PASSWORD_FREE_BASENAME', plugin_basename(PASSWORD_FREE_FILE));
const PASSWORD_FREE_NAME = 'PasswordFree';
const PASSWORD_FREE_VERSION = '1.1.0';
const PASSWORD_FREE_SCHEDULE = 'password_free_schedule';

//NoPass url
const PASSWORD_FREE_ENV = 'https://wordpress.nopass.us';

//outer endpoints
const PASSWORD_FREE_OUTER_ROTE = 'wp-no-pass/api';
const PASSWORD_FREE_API_VERSION = '/api/v1';
const PASSWORD_FREE_ACTIVATION_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/WordPress/activate';
const PASSWORD_FREE_DEACTIVATION_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/WordPress/deactivate';
const PASSWORD_FREE_UNINSTALL_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/WordPress/uninstall';
const PASSWORD_FREE_REGISTER_VERIFICATION_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/Customers/register/confirm';
const PASSWORD_FREE_AUTH_VERIFICATION_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/Customers/auth/confirm';
const PASSWORD_FREE_UPDATE_EMAIL_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/Customers/update/info';
const PASSWORD_FREE_UPDATE_SITE_INFO_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/WordPress/site/update/info';
const PASSWORD_FREE_UPDATE_SITE_ADDRESS_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/WordPress/site/update/url';
const PASSWORD_FREE_DELETE_CUSTOMER_URL = PASSWORD_FREE_ENV . PASSWORD_FREE_API_VERSION . '/customers/delete/';

//inner endpoints
const PASSWORD_FREE_INNER_ROTE = 'wp-no-pass/inner';
const PASSWORD_FREE_INNER_PREFIX = '/index.php/wp-json/';
const PASSWORD_FREE_CLOSE_ACTIVATION_POPUP_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/activation/popup/stop';
const PASSWORD_FREE_CLOSE_SYNCHRONIZATION_POPUP_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/synchronization/popup/stop';
const PASSWORD_FREE_CLOSE_SYNCHRONIZATION_FAIL_POPUP_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/synchronization/fail/popup/stop';
const PASSWORD_FREE_CLOSE_SYNCHRONIZATION_GET_STATUS_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/synchronization/status';
const PASSWORD_FREE_DEFAULT_BUTTON_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/default/buttons/switch/';
const PASSWORD_FREE_SHORT_CODE_DEACTIVATION_REMOVE_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/shortcode/deactivation/remove/';
const PASSWORD_FREE_CUSTOMIZATION_APPLY_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/customization/apply';
const PASSWORD_FREE_CUSTOMIZATION_APPLY_DEFAULT_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/customization/apply/default';
const PASSWORD_FREE_CUSTOMIZATION_DEFAULT_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/customization/default';
const PASSWORD_FREE_BUTTONS_ARRAYS_URL = PASSWORD_FREE_API_CURRENT_URL . PASSWORD_FREE_INNER_PREFIX . PASSWORD_FREE_INNER_ROTE . '/buttons/arrays';

//db titles
const PASSWORD_FREE_IS_ACTIVATED_FOR_POPUP = 'password_free_is_activated_for_popup';
const PASSWORD_FREE_IS_ACTIVATED_FOR_REDIRECT = 'password_free_is_activated_for_redirect';
const PASSWORD_FREE_IS_ACTIVATED = 'password_free_is_activated';
const PASSWORD_FREE_NETWORK_WIDE = 'password_free_network_wide';
const PASSWORD_FREE_ACTIVATION_CODE = 'password_free_activation_code';
const PASSWORD_FREE_ID = 'password_free_id';
const PASSWORD_FREE_TOKEN = 'password_free_token';
const PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API = 'password_free_token_for_access_to_api';
const PASSWORD_FREE_ACTIVATION_ERROR = 'password_free_activation_error';
const PASSWORD_FREE_REGISTER_URL = 'password_free_register_url';
const PASSWORD_FREE_LOGIN_URL = 'password_free_login_url';
const PASSWORD_FREE_REDIRECT_CODE = 'password_free_redirect_code_';
const PASSWORD_FREE_CUSTOMER_ID = 'password_free_customer_id';
const PASSWORD_FREE_IS_DEFAULT_BUTTON_ON = 'password_free_is_default_button_on';
const PASSWORD_FREE_INNER_KEY = 'password_free_inner_key';
const PASSWORD_FREE_USER_VALID = 'password_free_user_valid_';
const PASSWORD_FREE_SHORTCODE_DEACTIVATION_REMOVE = 'password_free_short_code_deactivation_remove';
const PASSWORD_FREE_VERSIONS = 'password_free_versions';
const PASSWORD_FREE_UPDATE_NOTIFICATION = 'password_free_update_notification';
const PASSWORD_FREE_GRACE_PERIOD_END = 'password_free_grace_period_end';
const PASSWORD_FREE_CUSTOMIZATION_DEFAULT = 'password_free_customization_default';
const PASSWORD_FREE_CUSTOMIZATION_CUSTOM = 'password_free_customization_custom';
const PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT = 'password_free_customization_alignment';
const PASSWORD_FREE_CUSTOMIZATION_LOGO = 'password_free_customization_logo';
const PASSWORD_FREE_CUSTOMIZATION_FONTS = 'password_free_customization_fonts';
const PASSWORD_FREE_CUSTOMIZATION_BUTTONS = 'password_free_customization_buttons';
const PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL = 'password_free_redirect_after_register_url';
const PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL = 'password_free_redirect_after_auth_url';
const PASSWORD_FREE_IS_WAS_ACTIVATED = 'password_free_is_was_activated';
const PASSWORD_FREE_IS_MAIN_URL = 'password_free_is_main_url';
const PASSWORD_FREE_SYNCHRONIZATION_STATUS = 'password_free_synchronization_status';
const PASSWORD_FREE_SYNCHRONIZATION_ID = 'password_free_synchronization_id';
const PASSWORD_FREE_SYNCHRONIZATION_START_POPUP = 'password_free_synchronization_start_popup';
const PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP = 'password_free_synchronization_failed_popup';
const PASSWORD_FREE_SYNCHRONIZATION_FAIL_BLOCK = 'password_free_synchronization_fail_block';
const PASSWORD_FREE_SYNCHRONIZATION_PROCESS_SHORT_POPUP = 'password_free_synchronization_process_short_popup';

//synchronization const
const PASSWORD_FREE_SYNCHRONIZATION_CONST_NOT_REQUIRED = 'NotRequired';
const PASSWORD_FREE_SYNCHRONIZATION_CONST_PROCESSING = 'Processing';
const PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL = 'Successful';
const PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED = 'Failed';
const PASSWORD_FREE_SYNCHRONIZATION_CONST_INTERRUPTED = 'Interrupted';
$password_free_sync_status =  get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS);
if ($password_free_sync_status == '') {
    update_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS, PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL);
}
define("PASSWORD_FREE_IS_SYNC_STATUS_CORRECT", $password_free_sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL
    || $password_free_sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_NOT_REQUIRED);
define("PASSWORD_FREE_IS_SYNC_STATUS_INCORRECT", $password_free_sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED
    || $password_free_sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_INTERRUPTED || $password_free_sync_status == '');


const PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR = 'ERROR';
const PASSWORD_FREE_UPDATE_NOTIFICATION_WARNING = 'WARNING';

//short code titles
const PASSWORD_FREE_SHORT_CODE_SIGN_IN = 'passwordfree_sign_in';
const PASSWORD_FREE_SHORT_CODE_SIGN_UP = 'passwordfree_sign_up';

//endpoints constants
const PASSWORD_FREE_AUTH_SESSION_ID_PARAM = 'authSessionId';
const PASSWORD_FREE_REGISTER_SESSION_ID_PARAM = 'registerSessionId';
const PASSWORD_FREE_STATUS_CODE_301 = 301;

