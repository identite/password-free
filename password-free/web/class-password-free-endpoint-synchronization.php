<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Endpoint_Synchronization
{
    public function password_free_update_sync_status()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/users/synchronization', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_update_sync_status_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_get_sync_status()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/synchronization/status', array(
            'methods' => 'GET',
            'callback' => [$this, 'password_free_get_sync_status_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_stop_show_sync_popup()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/synchronization/popup/stop', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_stop_show_sync_popup_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_stop_show_sync_fail_popup()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/synchronization/fail/popup/stop', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_stop_show_sync_fail_popup_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_update_sync_status_func($request)
    {
        $body = json_decode($request->get_body(), true);
        $sync_id = $body['syncId'];
        $sync_status = $body['synchronizationStatus'];
        $sync_id_from_db = get_option(PASSWORD_FREE_SYNCHRONIZATION_ID);
        if ($sync_id === $sync_id_from_db) {
            if (is_multisite()) {
                $blogs = get_sites();
                $blog_ids_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
                if (get_site_option('active_sitewide_plugins')[PASSWORD_FREE_BASENAME] != '') {
                    foreach ($blogs as $blog) {
                        switch_to_blog($blog->blog_id);
                        $this->add_db_value($sync_status);
                        restore_current_blog();
                    }
                } elseif (!empty($blog_ids_with_active_plugin)) {
                    foreach ($blog_ids_with_active_plugin as $blog_id) {
                        switch_to_blog($blog_id);
                        $this->add_db_value($sync_status);
                        restore_current_blog();
                    }
                }
            } else {
                $this->add_db_value($sync_status);
            }
        }
    }

    public function password_free_stop_show_sync_popup_func($request)
    {
        $sync_status = $request->get_body();
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP);
        if ($sync_status === PASSWORD_FREE_SYNCHRONIZATION_CONST_PROCESSING) {
            update_option(PASSWORD_FREE_SYNCHRONIZATION_PROCESS_SHORT_POPUP, true);
        } elseif ($sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED || $sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_INTERRUPTED) {
            update_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP, true);
        }
    }

    public function password_free_stop_show_sync_fail_popup_func()
    {
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP);
    }

    public function password_free_get_sync_status_func() {
        return get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS);
    }

    private function add_db_value($sync_status) {
        if ($sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED || $sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_INTERRUPTED) {
            delete_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP);
            update_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP, true);
            update_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_BLOCK, true);
        } elseif ($sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL) {
            delete_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP);
            delete_option(PASSWORD_FREE_SYNCHRONIZATION_PROCESS_SHORT_POPUP);
            delete_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP);
            delete_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_BLOCK);
        }
        update_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS, $sync_status);
    }
}