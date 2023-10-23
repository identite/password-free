<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Endpoint_Activation
{

    public function password_free_add_endpoint_get_token()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/token', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_get_token'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_check_code']
        ));
    }

    public function password_free_add_endpoint_get_info()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/info', array(
            'methods' => 'GET',
            'callback' => [$this, 'password_free_get_info'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_get_info()
    {
        $email = is_multisite() ? get_site_option('admin_email') : get_option('admin_email');

        $admin_info = get_user_meta(get_user_by_email($email)->ID);
        $site_id = get_option(PASSWORD_FREE_ID);
        if (is_multisite()) {
            switch_to_blog(get_main_site_id());
            $site_icon = get_site_icon_url();
            restore_current_blog();
        } else {
            $site_icon = get_site_icon_url();
        }
            return [
                "id" => $site_id != '' ? $site_id : NULL,
                "url" => get_site_url(),
                "name" => is_multisite() ? get_site_option('site_name') : get_option('blogname'),
                "usersCount" => get_user_count(),
                "faviconUrl" => $site_icon,
                "admin" => [
                    "email" => $email,
                    "firstName" => $admin_info['first_name'][0],
                    "lastName" => $admin_info['last_name'][0]
                ]
            ];
    }

    public function password_free_get_token()
    {
        $token = $this->generate_token();
        $is_token_exist = get_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API) != '';
        $is_token_exist ? update_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API, $token) :
            add_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API, $token);
        delete_transient(PASSWORD_FREE_ACTIVATION_CODE);
        return ["accessToken" => $token];
    }

    private function generate_token(): string
    {
        $length = 64;
        $result = '';
        do {
            $bytes = random_bytes(($length + 3 - strlen($result)) * 2);
            $result .= str_replace(['/', '+', '='], ['', '', ''], base64_encode($bytes));
        } while (strlen($result) < $length + 3);
        return substr($result, 0, $length);
    }
}