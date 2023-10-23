<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Endpoint_Update
{

    public function password_free_update_info()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/update/info', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_update_info_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_update_info_func($request)
    {
        $body = json_decode($request->get_body(), true);
        $versions = $body['supportedApiVersions'];
        $update_notification_type = Password_Free_Update::password_free_set_update_values($versions);

        if (is_multisite()) {
            $blogs = get_sites();
            $blogs_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
            if (get_site_option('active_sitewide_plugins')[PASSWORD_FREE_BASENAME] != '') {
                foreach ($blogs as $blog) {
                    switch_to_blog($blog->blog_id);
                    update_option(PASSWORD_FREE_VERSIONS, $versions);
                    update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $update_notification_type);
                    restore_current_blog();
                }
            }  elseif (!empty($blogs_with_active_plugin)) {
                foreach ($blogs_with_active_plugin as $blog_id) {
                    switch_to_blog($blog_id);
                    update_option(PASSWORD_FREE_VERSIONS, $versions);
                    update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $update_notification_type);
                    restore_current_blog();
                }
            }
        } else {
            update_option(PASSWORD_FREE_VERSIONS, $versions);
            update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $update_notification_type);
        }
    }
}