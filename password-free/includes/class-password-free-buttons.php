<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Buttons
{
    public function password_free_add_default_buttons(): string
    {
        wp_enqueue_style("password_free_button-login-page", PASSWORD_FREE_URL . 'admin/css/buttons/password-free-button-login-page.css');
        $is_register = '';
        if ($GLOBALS['pagenow'] === 'wp-login.php' && @ $_REQUEST['action'] === 'register') {
            $is_register = true;
        } elseif ($GLOBALS['pagenow'] === 'wp-login.php') {
            $is_register = false;
        }
        return self::password_free_button($is_register, true, true);
    }

    public function password_free_add_default_buttons_multisite()
    {
        echo self::password_free_button(true, false, true);
    }

    public static function password_free_button($isRegister, $is_need_additional_info, $is_need_redirect): string
    {
        if ($is_need_redirect) {
            $blog_id = is_multisite() ? get_current_blog_id() : '';
            if (!file_exists(PASSWORD_FREE_PATH . 'admin/css/buttons/password-free-button-style' . $blog_id . '.css')) {
                password_free_load_customization_arrays(true);
            }
            wp_enqueue_style("password_free_button", PASSWORD_FREE_URL . 'admin/css/buttons/password-free-button.css');
            wp_enqueue_style("password_free_button-style", PASSWORD_FREE_URL . 'admin/css/buttons/password-free-button-style' . $blog_id . '.css');
            wp_enqueue_script("password_free_button-js", PASSWORD_FREE_URL . 'admin/js/password-free-button.js', '', '', true);

            $array = array(
                'pfRegisterRedirectUrl' => self::get_redirect_url(true),
                'pfAuthRedirectUrl' => self::get_redirect_url(false),
            );
            wp_localize_script('password_free_button-js', 'buttonVariables', $array);
        }
        $motivation_text = $is_need_redirect ? self::get_motivation_text($isRegister) : '';
        $text = self::get_button_text($isRegister);
        return self::get_buttons_template($text, $is_need_additional_info, $motivation_text, $isRegister);
    }

    private static function get_buttons_template($text, $is_need_additional_info, $motivation_text, $isRegister): string
    {
        $additional_info_after = '';
        $additional_info_before = '';
        if ($is_need_additional_info) {
            $additional_info_before = '<div class="password-free-login-page-wrapper">';
            $additional_info_after =
                '<div class="password-free-or-wrapper">
                 <div class="password-free-login-or-line-before"></div>
                 <div class="password-free-login-page-or">OR</div>
                 <div class="password-free-login-or-line-after"></div>
             </div>
             </div>';
        }
        $on_click = '';
        $button_motivation_text = '';
        if ($motivation_text != '') {
            $on_click = 'onclick="pfOnclick(' . $isRegister . ')"';
            $button_motivation_text = '<div class="password_free_motivation_text">' . $motivation_text . '</div>';
        }
        return $additional_info_before .
            '<div class="password-free-button-wrapper">'
            . $button_motivation_text .
            '<div class="password-free-button-part">
            <div class="password-free-button"' . $on_click . '>
                <img src="" alt="img" >
                <div>' . $text . '</div>
             </div>
             </div>
             </div>' . $additional_info_after;
    }

    private static function get_button_text($isRegister): string
    {
        return $isRegister ? 'Sign up PasswordFree' : 'Sign in PasswordFree';
    }

    private static function get_redirect_url($isRegister): string
    {
        $blog_id = get_current_blog_id();
        $link = $isRegister ? get_option(PASSWORD_FREE_REGISTER_URL) : get_option(PASSWORD_FREE_LOGIN_URL);
        return $link . password_free_redirect_query_param() . '&blogId=' . $blog_id;
    }

    private static function get_motivation_text($isRegister)
    {
        return $isRegister ? 'Sign up <span>fast</span> without a password ' : 'Forgot your password or<br> want to Sign in <span>fast</span> without a password?';
    }
}



