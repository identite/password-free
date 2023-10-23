<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Activator
{

    public static function activation_requests()
    {
        $code = self::generate_string(10);
        set_transient(PASSWORD_FREE_ACTIVATION_CODE, $code);
        $data = json_encode(array('url' => get_site_url(), 'code' => $code));
        $ch = curl_init(PASSWORD_FREE_ACTIVATION_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2500);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = json_decode(curl_exec($ch), true);
        $errors = $body['errors'];
        $result = $body['result'];
        $activation_error = '';
        $id = '';
        $token = '';
        $register_utl = '';
        $login_utl = '';
        $inner_key = '';
        $is_default_button_on = '';
        $versions = '';
        $update_notification = '';
        $synchronization_status = '';
        $synchronization_id = '';
        $current_api_token = get_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API);
        if ($errors != '' && $errors['code'] != NULL) {
            $activation_error = ['code' => $body['errors']['code'],
                'message' => $body['errors']['message']];
        } else if ($body['token']['accessToken'] == '') {
            $activation_error = 'token was not got';
        } else if ($result['externalSiteId'] == '') {
            $activation_error = 'id was not got';
        } else if ($result['registerUrl'] == '' || $result['loginUrl'] == '') {
            $activation_error = 'urls were not got';
        } else {
            $id = $result['externalSiteId'];
            $token =  'Bearer ' . $body['token']['accessToken'];
            $register_utl = $result['registerUrl'];
            $login_utl = $result['loginUrl'];
            $inner_key = self::generate_string(8);
            $is_default_button_on = is_multisite() ? password_free_multi_sites_default_buttons_on() : get_option('users_can_register');
            $versions = $result['supportedApiVersions'];
            $update_notification = Password_Free_Update::password_free_set_update_values($versions);
            $synchronization_status = $result['synchronizationStatus'] == '' ? PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED : $result['synchronizationStatus'];
            $synchronization_id = $result['syncId'];
            delete_transient(PASSWORD_FREE_ACTIVATION_ERROR);
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code == 200 && is_multisite()) {
            add_option(PASSWORD_FREE_IS_MAIN_URL, true);
        }
        if ($activation_error != '') {
            $result_array =  [PASSWORD_FREE_ACTIVATION_ERROR => $activation_error];
        } else {
            $result_array =  [
                PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API => $current_api_token,
                PASSWORD_FREE_ID => $id,
                PASSWORD_FREE_TOKEN => $token,
                PASSWORD_FREE_REGISTER_URL => $register_utl,
                PASSWORD_FREE_LOGIN_URL => $login_utl,
                PASSWORD_FREE_INNER_KEY => $inner_key,
                PASSWORD_FREE_IS_DEFAULT_BUTTON_ON => $is_default_button_on,
                PASSWORD_FREE_VERSIONS => $versions,
                PASSWORD_FREE_UPDATE_NOTIFICATION => $update_notification,
                PASSWORD_FREE_SYNCHRONIZATION_STATUS => $synchronization_status,
                PASSWORD_FREE_SYNCHRONIZATION_ID => $synchronization_id
            ];
        }
        return $result_array;
    }

    private static function generate_string($code_length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $code_length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
