<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Endpoint_Inner
{

    public function password_free_stop_show_activation_popup()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/activation/popup/stop', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_stop_show_activation_popup_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_default_buttons_switch()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/default/buttons/switch/(?P<id>[0,1])', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_default_buttons_switch_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_short_code_deactivation_remove()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/shortcode/deactivation/remove/(?P<id>[0,1])', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_short_code_deactivation_remove_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_customization_apply()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/customization/apply', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_customization_apply_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_customization_apply_default()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/customization/apply/default', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_customization_apply_default_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_customization_default()
    {
        register_rest_route(PASSWORD_FREE_INNER_ROTE, '/customization/default', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_customization_default_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_inner_auth']
        ));
    }

    public function password_free_customization_apply_default_func()
    {
        delete_option(PASSWORD_FREE_CUSTOMIZATION_CUSTOM);
        delete_option(PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL);
        delete_option(PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL);
        $array = get_option(PASSWORD_FREE_CUSTOMIZATION_DEFAULT);
        $data = Password_Free_Buttons_Style::password_free_get_css_data($array, '', '');
        $blog_id = is_multisite() ? get_current_blog_id() : '';
        file_put_contents(PASSWORD_FREE_PATH . 'admin/css/buttons/password-free-button-style' . $blog_id . '.css', $data, LOCK_EX);
    }

    public function password_free_customization_default_func()
    {
        return ['baseArray' => get_option(PASSWORD_FREE_CUSTOMIZATION_DEFAULT),
            'alignmentArray' => get_option(PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT),
            'logoArray' => get_option(PASSWORD_FREE_CUSTOMIZATION_LOGO),
            'buttonsArray' => get_option(PASSWORD_FREE_CUSTOMIZATION_BUTTONS)];

    }

    public function password_free_customization_apply_func($request)
    {
        $body = json_decode($request->get_body(), true);
        $custom_array = $body['customArray'];
        $width = $body['width'];
        $height = $body['height'];
        update_option(PASSWORD_FREE_CUSTOMIZATION_CUSTOM, $custom_array);
        $this->update_redirect_url($custom_array['register_url'], $custom_array['auth_url']);
        $data = Password_Free_Buttons_Style::password_free_get_css_data($custom_array, $width, $height);
        $blog_id = is_multisite() ? get_current_blog_id() : '';
        file_put_contents(PASSWORD_FREE_PATH . 'admin/css/buttons/password-free-button-style' . $blog_id . '.css', $data, LOCK_EX);
    }

    public function password_free_stop_show_activation_popup_func()
    {
        delete_option(PASSWORD_FREE_IS_ACTIVATED_FOR_POPUP);
    }

    public function password_free_default_buttons_switch_func($request)
    {
        $is_switch_on = $request->get_param('id');
        update_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON, $is_switch_on);
    }

    public function password_free_short_code_deactivation_remove_func($request)
    {
        $is_switch_on = $request->get_param('id');
        update_option(PASSWORD_FREE_SHORTCODE_DEACTIVATION_REMOVE, $is_switch_on);
    }

    private function update_redirect_url($register_url, $auth_url) {
        if ($register_url == '') {
            delete_option(PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL);
        } else {
            update_option(PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL, $register_url);
        }
        if ($auth_url == '') {
            delete_option(PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL);
        } else {
            update_option(PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL, $auth_url);
        }
    }
}