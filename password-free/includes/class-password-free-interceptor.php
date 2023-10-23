<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Interceptor
{

    public function password_free_intercept_users_can_register($old_value, $value)
    {
        update_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON, $value);
    }

    public function password_free_intercept_add_new_site(WP_Site $new_site)
    {
        if (get_site_option('active_sitewide_plugins')[PASSWORD_FREE_BASENAME] != '') {
            switch_to_blog(get_main_site_id());
            $db_params = password_free_get_blog_db_params();
            restore_current_blog();
            switch_to_blog($new_site->blog_id);
            password_free_add_db_values_to_blog($db_params);
            password_free_activation_actions(false);
            restore_current_blog();
        }
    }
}
