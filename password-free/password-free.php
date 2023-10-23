<?php
/**
 * Plugin Name:       PasswordFree
 * Description:       PasswordFree™ is an essential customer biometric MFA for fast, easy, and more secure login experiences. No coding, Boost sales, Free app.
 * Version:           1.1.0
 * Requires PHP:      7.4 or above
 * Author:            Identite
 * Author URI:        https://www.identite.us/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       password-free
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
const PASSWORD_FREE_FILE = __FILE__;
require plugin_dir_path(__FILE__) . 'util/password-free-constants.php';
require plugin_dir_path(__FILE__) . 'includes/class-password-free.php';

register_activation_hook(__FILE__, 'password_free_activate');
register_deactivation_hook(__FILE__, 'password_free_deactivate');
register_uninstall_hook(__FILE__, 'password_free_uninstall');

function password_free_activate($networkwide)
{
    set_transient(PASSWORD_FREE_IS_ACTIVATED, true);
    set_transient(PASSWORD_FREE_NETWORK_WIDE, $networkwide);
}

function password_free_after_activate_actions()
{
    if (get_transient(PASSWORD_FREE_IS_ACTIVATED)) {
        $networkwide = get_transient(PASSWORD_FREE_NETWORK_WIDE);
        require_once plugin_dir_path(__FILE__) . 'includes/class-password-free-activator.php';
        if (function_exists('is_multisite') && is_multisite()) {
            $blogs = get_sites();
            $blog_ids_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
            if ($networkwide) {
                if (false == is_super_admin()) {
                    return;
                }
                if ((empty($blog_ids_with_active_plugin))) {
                    $db_params = Password_Free_Activator::activation_requests();
                } else {
                    switch_to_blog($blog_ids_with_active_plugin[0]);
                    $db_params = password_free_get_blog_db_params();
                    restore_current_blog();
                }
                foreach ($blogs as $blog) {
                    $blog_id = $blog->blog_id;
                    if (!in_array($blog_id, $blog_ids_with_active_plugin)) {
                        switch_to_blog($blog->blog_id);
                        password_free_add_db_values_to_blog($db_params);
                        password_free_activation_actions(false);
                        restore_current_blog();
                    }
                }
            } else {
                if (false == current_user_can('activate_plugins')) {
                    return;
                }
                if ((count($blog_ids_with_active_plugin) == 1)) {
                    $db_params = Password_Free_Activator::activation_requests();
                    $id = $db_params[PASSWORD_FREE_ID];
                    if ($id != '') {
                        foreach ($blogs as $blog) {
                            $blog_id = $blog->blog_id;
                            if ($blog_id != get_current_blog_id()) {
                                switch_to_blog($blog_id);
                                update_option(PASSWORD_FREE_ID, $id);
                                restore_current_blog();
                            }
                        }
                    }
                } else {
                    $blog_id = '';
                    for ($i = 0; $i <= count($blog_ids_with_active_plugin); $i++) {
                        if ($blog_ids_with_active_plugin[$i] != get_current_blog_id()) {
                            $blog_id = $blog_ids_with_active_plugin[$i];
                            break;
                        }
                    }
                    switch_to_blog($blog_id);
                    $db_params = password_free_get_blog_db_params();
                    restore_current_blog();
                }
                password_free_add_db_values_to_blog($db_params);
                password_free_activation_actions(true);
            }
        } else {
            password_free_add_db_values_to_blog(Password_Free_Activator::activation_requests());
            password_free_activation_actions(true);
        }
        delete_transient(PASSWORD_FREE_IS_ACTIVATED);
        delete_transient(PASSWORD_FREE_NETWORK_WIDE);
    }
    if (get_transient(PASSWORD_FREE_IS_ACTIVATED_FOR_REDIRECT)) {
        delete_transient(PASSWORD_FREE_IS_ACTIVATED_FOR_REDIRECT);
        exit(wp_redirect(PASSWORD_FREE_API_CURRENT_URL . '/wp-admin/admin.php?page=password-free-overview'));
    }
}

function password_free_deactivate($networkwide)
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-password-free-deactivator.php';
    if (function_exists('is_multisite') && is_multisite()) {
        $blogs = get_sites();
        $blog_ids_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
        if ($networkwide) {
            if (false == is_super_admin()) {
                return;
            }
            if (empty($blog_ids_with_active_plugin)) {
                Password_Free_Deactivator::deactivate_request();
            }
            foreach ($blogs as $blog) {
                $blog_id = $blog->blog_id;
                if (!in_array($blog_id, $blog_ids_with_active_plugin)) {
                    switch_to_blog($blog->blog_id);
                    Password_Free_Deactivator::deactivate();
                    restore_current_blog();
                }
            }
        } else {
            if (false == current_user_can('activate_plugins')) {
                return;
            }
            if (count($blog_ids_with_active_plugin) == 1) {
                Password_Free_Deactivator::deactivate_request();
            } else {
                if (get_option(PASSWORD_FREE_IS_MAIN_URL)) {
                    for ($i = 0; $i <= count($blog_ids_with_active_plugin); $i++) {
                        if ($blog_ids_with_active_plugin[$i] != get_current_blog_id()) {
                            switch_to_blog($blog_ids_with_active_plugin[$i]);
                            $site_url = get_site_url();
                            $notification = new Password_Free_Notification();
                            $notification->send_change_url_request($site_url);
                            update_option(PASSWORD_FREE_IS_MAIN_URL, true);
                            restore_current_blog();
                            delete_option(PASSWORD_FREE_IS_MAIN_URL);
                            break;
                        }
                    }
                }
            }
            Password_Free_Deactivator::deactivate();
        }
    } else {
        Password_Free_Deactivator::deactivate();
        Password_Free_Deactivator::deactivate_request();
    }
}

function password_free_uninstall()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-password-free-uninstall.php';
    if (function_exists('is_multisite') && is_multisite()) {
        $blogs = get_sites();
        $blog_ids_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
        if (false == is_super_admin()) {
            return;
        }
        if (!empty($blog_ids_with_active_plugin)) {
            $token = get_option(PASSWORD_FREE_TOKEN);
            foreach ($blog_ids_with_active_plugin as $blog_id) {
                switch_to_blog($blog_id);
                if ($token == '') {
                    $token = get_option(PASSWORD_FREE_TOKEN);
                }
                deactivate_plugins( PASSWORD_FREE_BASENAME );
                restore_current_blog();
            }
            update_option(PASSWORD_FREE_TOKEN, $token);
        }

        Password_Free_Uninstall::uninstall_request();
        foreach ($blogs as $blog) {
            switch_to_blog($blog->blog_id);
            Password_Free_Uninstall::uninstall();
            restore_current_blog();
        }
    } else {
        Password_Free_Uninstall::uninstall_request();
        Password_Free_Uninstall::uninstall();
    }
}

function password_free_activation_actions($isSingle)
{
    $synchronization_status = get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS);
    if ($synchronization_status === PASSWORD_FREE_SYNCHRONIZATION_CONST_PROCESSING) {
    }
    if (get_option(PASSWORD_FREE_IS_WAS_ACTIVATED) == '') {
        add_option(PASSWORD_FREE_IS_ACTIVATED_FOR_POPUP, true);
        add_option(PASSWORD_FREE_IS_WAS_ACTIVATED, true);
    }
    if ($isSingle) {
        set_transient(PASSWORD_FREE_IS_ACTIVATED_FOR_REDIRECT, true);
    }
    wp_schedule_event(strtotime('00:00:05'), 'daily', PASSWORD_FREE_SCHEDULE);
}

add_action(PASSWORD_FREE_SCHEDULE, 'password_free_versions_update');

function password_free_versions_update()
{
    $notification_type =  Password_Free_Update::password_free_set_update_values(get_option(PASSWORD_FREE_VERSIONS));
    update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $notification_type);
}

function password_free_short_code_sign_up_button()
{
    return Password_Free_Short_Code::password_free_short_code_button(true);
}

function password_free_short_code_sign_in_button()
{
    return Password_Free_Short_Code::password_free_short_code_button(false);
}

function password_free_hide_short_codes()
{
    Password_Free_Short_Code::password_free_hide_short_codes();
}

function password_free_add_activation_actions()
{
    add_action('activated_plugin', 'password_free_after_activate_actions');
}

function password_free_add_short_code_buttons()
{
    if (!get_transient(PASSWORD_FREE_ACTIVATION_ERROR) && PASSWORD_FREE_IS_SYNC_STATUS_CORRECT) {
        add_shortcode(PASSWORD_FREE_SHORT_CODE_SIGN_UP, 'password_free_short_code_sign_up_button');
        add_shortcode(PASSWORD_FREE_SHORT_CODE_SIGN_IN, 'password_free_short_code_sign_in_button');
        add_action('init', 'password_free_hide_short_codes');
    }
}

function run_password_free()
{
    if (get_option(PASSWORD_FREE_ID) == '') {
        set_transient(PASSWORD_FREE_ACTIVATION_ERROR, true);
    }
    $plugin = new Password_Free();
    password_free_add_activation_actions();
    password_free_add_short_code_buttons();
    $plugin->run();
}

run_password_free();
