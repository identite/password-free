<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Auth
{

    public function __construct()
    {
    }

    public static function password_free_auth($request): bool
    {
        $token = get_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API);
        if ($token != $request->get_header('authorization') || $token == '') {
            return false;
        }
        return true;
    }

    public static function password_free_check_code($request): bool
    {
        $body = json_decode($request->get_body(), true);
        $code = get_transient(PASSWORD_FREE_ACTIVATION_CODE);
        if ($code != $body["code"] || $code == '') {
            return false;
        }
        return true;
    }

    public static function password_free_inner_auth($request): bool
    {
        $key = get_option(PASSWORD_FREE_INNER_KEY);
        if ($key != $request->get_header('authorization') || $key == '') {
            return false;
        }
        return true;
    }
}