<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Notification_Update
{

    public static function password_free_notification_update_warning()
    {
        wp_enqueue_style("password-free-notification-update-warning", PASSWORD_FREE_URL . 'admin/css/password-free-notification-update-warning.css');
        $grace_date = strtotime(get_transient(PASSWORD_FREE_GRACE_PERIOD_END));
        $date = date("F", $grace_date) . ' ' . date("j", $grace_date) . date("S", $grace_date);
        ?>
        <div class="password-free-notification-update-warning-wrapper">
            <div class="password-free-notification-update-warning-line"></div>
            <div class="password-free-notification-update-warning-text">
                There is a new version of PasswordFree™ available.<br>
                <a href="<?php echo PASSWORD_FREE_PLUGINS_PAGE_URL ?>">Update version</a> till <?php echo $date ?> or
                your users will lose access to PasswordFree™ functionality.
            </div>
        </div>
        <?php
    }

    public static function password_free_notification_update_error()
    {
        wp_enqueue_style("password-free-notification-update-error", PASSWORD_FREE_URL . 'admin/css/password-free-notification-update-error.css');
        ?>
        <div class="password-free-notification-update-error-wrapper">
            <div class="password-free-notification-update-error-line"></div>
            <div class="password-free-notification-update-error-text">
                Your users will have no access to PasswordFree™ functionality until you <a
                        href="<?php echo PASSWORD_FREE_PLUGINS_PAGE_URL ?>">update PasswordFree™ version</a>.<br>
                Your users can sign in via password only.
            </div>
        </div>
        <?php
    }
}