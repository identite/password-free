<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free
{

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct()
    {
        if (defined('PASSWORD_FREE_VERSION')) {
            $this->version = PASSWORD_FREE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = PASSWORD_FREE_NAME;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_notification_hooks();
        $this->define_web_endpoints();
        $this->define_buttons();
        $this->define_interceptors();
        $this->define_update();
    }

    private function load_dependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-password-free-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-notification.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-interceptor.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-buttons.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-db-query.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-update.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-password-free-buttons-style.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-auth.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-endpoint-activation.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-endpoint-user-auth.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-endpoint-inner.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-endpoint-update.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'web/class-password-free-endpoint-synchronization.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/password-free-header.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/notifications/class-password-free-notification-update.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-password-free-short-code.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'util/password-free-util-functions.php';
        $this->loader = new Password_Free_Loader();
    }

    private function define_admin_hooks()
    {
        $password_free_admin = new Password_Free_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_menu', $password_free_admin, 'password_free_add_admin_menu');
        $this->loader->add_action('admin_enqueue_scripts', $password_free_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $password_free_admin, 'enqueue_scripts');
    }

    private function define_notification_hooks()
    {
        $password_free_notification = new Password_Free_Notification();
        if (is_multisite()) {
            $this->loader->add_action('wpmu_delete_user', $password_free_notification, 'password_free_notify_user_deleted', 10, 2);
        } else {
            $this->loader->add_action('delete_user', $password_free_notification, 'password_free_notify_user_deleted', 10, 2);
        }
        $this->loader->add_action('profile_update', $password_free_notification, 'password_free_notify_user_email_updated', 10, 2);
        $this->loader->add_action('update_option_siteurl', $password_free_notification, 'password_free_notify_url_updated', 10, 2);
        if (is_multisite()) {
            $this->loader->add_action('network_admin_email_change_email', $password_free_notification, 'password_free_notify_site_email_updated', 10, 2);
            $this->loader->add_action('wp_validate_site_deletion', $password_free_notification, 'password_free_notify_pre_delete_site', 10, 2);
        } else {
            $this->loader->add_action('update_option_admin_email', $password_free_notification, 'password_free_notify_site_email_updated', 10, 2);
        }
    }

    private function define_web_endpoints()
    {
        $password_free_endpoint_activation = new Password_Free_Endpoint_Activation();
        $this->loader->add_action('rest_api_init', $password_free_endpoint_activation, 'password_free_add_endpoint_get_token');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_activation, 'password_free_add_endpoint_get_info');

        $password_free_endpoint_signup = new Password_Free_Endpoint_User_Auth();
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_user_registration');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_redirect');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_email_validation');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_get_redirect_code');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_get_user_info');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_get_password_free_users');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_delete_user_password_free_marker');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_delete_users_password_free_marker');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_signup, 'password_free_endpoint_user_credentials_validation');

        $password_free_endpoint_inner = new Password_Free_Endpoint_Inner();
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_stop_show_activation_popup');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_default_buttons_switch');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_short_code_deactivation_remove');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_customization_apply');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_customization_apply_default');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_inner, 'password_free_customization_default');

        $password_free_endpoint_synchronization = new Password_Free_Endpoint_Synchronization();
        $this->loader->add_action('rest_api_init', $password_free_endpoint_synchronization, 'password_free_update_sync_status');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_synchronization, 'password_free_get_sync_status');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_synchronization, 'password_free_stop_show_sync_popup');
        $this->loader->add_action('rest_api_init', $password_free_endpoint_synchronization, 'password_free_stop_show_sync_fail_popup');

        $password_free_endpoint_update = new Password_Free_Endpoint_Update();
        $this->loader->add_action('rest_api_init', $password_free_endpoint_update, 'password_free_update_info');
    }

    private function define_buttons()
    {
        if (get_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON) && !get_option(PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR)
            && get_option(PASSWORD_FREE_UPDATE_NOTIFICATION) !== PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR
            && PASSWORD_FREE_IS_SYNC_STATUS_CORRECT) {
            $password_free_buttons = new Password_Free_Buttons();
            $this->loader->add_filter('login_message', $password_free_buttons, 'password_free_add_default_buttons');
            if (is_multisite()) {
                $this->loader->add_action('signup_hidden_fields', $password_free_buttons, 'password_free_add_default_buttons_multisite');
            }
        }
    }

    private function define_interceptors()
    {
        $password_free_interceptor = new Password_Free_Interceptor();
        $this->loader->add_action('update_option_users_can_register', $password_free_interceptor, 'password_free_intercept_users_can_register', 10, 2);
        if (is_multisite()) {
            $this->loader->add_action('wp_initialize_site', $password_free_interceptor, 'password_free_intercept_add_new_site', 900, 2);
        }
    }

    private function define_update()
    {
        $password_free_update = new Password_Free_Update();
        $this->loader->add_action('upgrader_process_complete', $password_free_update, 'password_free_update_complete', 10, 2);
    }

    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    public function get_loader()
    {
        return $this->loader;
    }

    public function get_version()
    {
        return $this->version;
    }

}
