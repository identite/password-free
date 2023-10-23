<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Short_Code
{

    public function __construct()
    {
    }

    static function password_free_short_code_button($isRegister)
    {
        $content = Password_Free_Buttons::password_free_button($isRegister, false, true);
        return do_shortcode($content, false);
    }

    static function password_free_hide_short_codes()
    {
        if (is_user_logged_in() || get_option(PASSWORD_FREE_UPDATE_NOTIFICATION) === PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR) {
            remove_shortcode(PASSWORD_FREE_SHORT_CODE_SIGN_UP);
            remove_shortcode(PASSWORD_FREE_SHORT_CODE_SIGN_IN);
            $hide_sign_up_short_code = function ($content) {
                $content = preg_replace('~\[' . PASSWORD_FREE_SHORT_CODE_SIGN_UP . '[^\]]*\]~', '', $content);
                return $content;
            };
            $hide_sign_in_short_code = function ($content) {
                $content = preg_replace('~\[' . PASSWORD_FREE_SHORT_CODE_SIGN_IN . '[^\]]*\]~', '', $content);
                return $content;
            };
            add_filter('the_content', $hide_sign_up_short_code, 5);
            add_filter('the_content', $hide_sign_in_short_code, 5);
        }
    }
}

