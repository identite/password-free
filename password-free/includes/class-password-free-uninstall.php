<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Uninstall
{

    public static function uninstall_request() {
        global $wpdb;
        $wpdb->delete($wpdb->usermeta, ['meta_key' => PASSWORD_FREE_CUSTOMER_ID]);
        $token = get_option(PASSWORD_FREE_TOKEN);
        $ch = curl_init(PASSWORD_FREE_UNINSTALL_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . $token, 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    public static function uninstall()
    {
        delete_option(PASSWORD_FREE_ID);
        delete_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API);
        delete_option(PASSWORD_FREE_TOKEN);
        delete_option(PASSWORD_FREE_REGISTER_URL);
        delete_option(PASSWORD_FREE_LOGIN_URL);
        delete_option(PASSWORD_FREE_INNER_KEY);
        delete_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON);
        delete_option(PASSWORD_FREE_SHORTCODE_DEACTIVATION_REMOVE);

        delete_option(PASSWORD_FREE_CUSTOMIZATION_DEFAULT);
        delete_option(PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT);
        delete_option(PASSWORD_FREE_CUSTOMIZATION_LOGO);
        delete_option(PASSWORD_FREE_CUSTOMIZATION_BUTTONS);
        delete_option(PASSWORD_FREE_CUSTOMIZATION_FONTS);
        delete_option(PASSWORD_FREE_CUSTOMIZATION_CUSTOM);
        delete_option(PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL);
        delete_option(PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL);
        delete_option(PASSWORD_FREE_IS_MAIN_URL);
        delete_option(PASSWORD_FREE_IS_WAS_ACTIVATED);
        delete_transient(PASSWORD_FREE_ACTIVATION_ERROR);
        Password_Free_Db_Query::remove_short_codes();
    }
}