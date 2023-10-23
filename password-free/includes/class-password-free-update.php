<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Update
{

    public function password_free_update_complete($upgrader_object, $hook_extra)
    {
        if ($hook_extra['type'] === 'plugin') {
            $versions = get_option(PASSWORD_FREE_VERSIONS);
            $notification_type = self::password_free_set_update_values($versions);
            $is_sync_exist = get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS) != '';
            if (function_exists('is_multisite') && is_multisite()) {
                $blogs = get_sites();
                foreach ($blogs as $blog) {
                    switch_to_blog($blog->blog_id);
                    if (!$is_sync_exist) {
                        update_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS, PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL);
                    }
                    update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $notification_type);
                    restore_current_blog();
                }
            } else {
                if (!$is_sync_exist) {
                    update_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS, PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL);
                }
                update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $notification_type);
            }
        }
    }

    public static function password_free_set_update_values($versions)
    {
        $password_free_major_version = explode('.', PASSWORD_FREE_VERSION)[0];
        $notification_type = '';
        $current_date = date_format(date_create(), "Y-m-d");
        if ($versions != '' && !empty($versions)) {
            foreach ($versions as $k => $v) {
                if ($v['majorVersion'] == $password_free_major_version) {
                    $grace_period_end = $v['endDate'];
                    if ($grace_period_end != '' && $grace_period_end > $current_date) {
                        $notification_type = PASSWORD_FREE_UPDATE_NOTIFICATION_WARNING;
                        set_transient(PASSWORD_FREE_GRACE_PERIOD_END, $v['endDate']);
                    } else if ($grace_period_end != '' && $grace_period_end < $current_date) {
                        $notification_type = PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR;
                    } else {
                        $notification_type = '';
                    }
                    break;
                } else {
                    $notification_type = PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR;
                }
            }
        } else {
            $notification_type = PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR;
        }
        return $notification_type;
    }
}

