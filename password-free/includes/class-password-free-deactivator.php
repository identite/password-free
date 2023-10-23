<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Deactivator
{

    public static function deactivate()
    {
        delete_transient(PASSWORD_FREE_ACTIVATION_CODE);
        if (get_option(PASSWORD_FREE_SHORTCODE_DEACTIVATION_REMOVE)) {
            Password_Free_Db_Query::remove_short_codes();
        }
        delete_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API);
        delete_option(PASSWORD_FREE_UPDATE_NOTIFICATION);
        delete_transient(PASSWORD_FREE_GRACE_PERIOD_END);
        delete_option(PASSWORD_FREE_VERSIONS);
        delete_option(PASSWORD_FREE_INNER_KEY);
        delete_option(PASSWORD_FREE_LOGIN_URL);
        delete_option(PASSWORD_FREE_REGISTER_URL);
        delete_option(PASSWORD_FREE_IS_MAIN_URL);
        delete_option(PASSWORD_FREE_IS_ACTIVATED_FOR_POPUP);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_ID);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_BLOCK);
        delete_option(PASSWORD_FREE_SYNCHRONIZATION_PROCESS_SHORT_POPUP);
        delete_transient(PASSWORD_FREE_ACTIVATION_ERROR);
    }

    public static function deactivate_request()
    {
        $ch = curl_init(PASSWORD_FREE_DEACTIVATION_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

