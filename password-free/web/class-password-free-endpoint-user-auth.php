<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Endpoint_User_Auth
{

    public function password_free_endpoint_user_registration()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/registration', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_register_user'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_get_redirect_code()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/redirect/code', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_get_redirect_code'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_redirect()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/auth', array(
            'methods' => 'GET',
            'callback' => [$this, 'password_free_redirect_user'],
            'args' => array(),
            'permission_callback' => function () {
                return true;
            }
        ));
    }

    public function password_free_endpoint_email_validation()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/email/validation', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_email_validation'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_get_user_info()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'password_free_user_info'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_delete_user_password_free_marker()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => [$this, 'password_free_delete_user_password_free_marker'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_delete_users_password_free_marker()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/users', array(
            'methods' => 'PUT',
            'callback' => [$this, 'password_free_delete_users_password_free_marker'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_get_password_free_users()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/users', array(
            'methods' => 'GET',
            'callback' => [$this, 'password_free_endpoint_get_password_free_users_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_user_credentials_validation()
    {
        register_rest_route(PASSWORD_FREE_OUTER_ROTE, '/user/credentials/validation', array(
            'methods' => 'POST',
            'callback' => [$this, 'password_free_endpoint_user_credentials_validation_func'],
            'args' => array(),
            'permission_callback' => ['Password_Free_Auth', 'password_free_auth']
        ));
    }

    public function password_free_endpoint_user_credentials_validation_func($request)
    {
        $body = json_decode($request->get_body(), true);
        $email = $body['email'];
        $password = $body['password'];
        if ($email == '' || $password == '') {
            wp_send_json_error("email or password is empty", 400);
        }
        $user = wp_authenticate($email, $password);
        $isValid = false;
        if (get_class($user) === 'WP_User') {
            $user_id = $user->ID;
            set_transient(PASSWORD_FREE_USER_VALID . $user_id, $user, 1800);
            $isValid = true;
        }
        return ['isValid' => $isValid];
    }

    public function password_free_delete_users_password_free_marker($request)
    {
        $body = json_decode($request->get_body(), true);
        $ids_array = $body['items'];
        $ids = implode(',', array_map('absint', $ids_array));
        global $wpdb;
        $query = $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id IN ($ids) AND meta_key = %s", PASSWORD_FREE_CUSTOMER_ID);
        $wpdb->query($query);
    }

    public function password_free_endpoint_get_password_free_users_func($request)
    {
        $page_size = intval($request->get_param('pageSize'));
        $page_number = intVal($request->get_param('pageNumber'));
        global $wpdb;
        $query_count = $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = %s", PASSWORD_FREE_CUSTOMER_ID);
        $total_count = intVal($wpdb->get_var($query_count));
        $result = array();
        if ($total_count > 0) {
            $offset = $page_size * $page_number - $page_size;
            $query_users = $wpdb->prepare("select us.ID as userId, us.user_email as email from $wpdb->users us left join $wpdb->usermeta nu on us.ID = nu.user_id where nu.meta_key = %s order by us.ID limit $offset, $page_size",
                PASSWORD_FREE_CUSTOMER_ID);
            $result = $wpdb->get_results($query_users);
        }
        return ['result' => ['items' => $result, 'pagination' => ['pageNumber' => $page_number,
            'pageSize' => $page_size,
            'totalCount' => $total_count]
        ]];
    }

    public function password_free_delete_user_password_free_marker($request)
    {
        $user_id = $request->get_param('id');
        $user = get_user_by('ID', $user_id);
        if ($user == '') {
            wp_send_json_error("user not found", 404);
        }
        delete_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID);
    }

    public function password_free_user_info($request)
    {
        $user_id = $request->get_param('id');
        $user = get_user_by('ID', $user_id);
        if ($user == '') {
            wp_send_json_error("user not found", 404);
        }
        return ['email' => $user->user_email];
    }

    public function password_free_register_user($request)
    {
        global $wpdb;
        $body = json_decode($request->get_body(), true);
        $email = $body["email"];
        $password_free_customer_id = $body['customerId'];
        $blogId = $body['blogId'];
        $session_id = $body[PASSWORD_FREE_REGISTER_SESSION_ID_PARAM];
        if (is_email($email) == '') {
            wp_send_json_error("incorrect email", 400);
        }
        $this->password_free_send_verification_request(PASSWORD_FREE_REGISTER_VERIFICATION_URL,
            PASSWORD_FREE_REGISTER_SESSION_ID_PARAM, $session_id);
        $is_customer_id_exist = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = %s AND meta_value = %s",
                    PASSWORD_FREE_CUSTOMER_ID,
                    $password_free_customer_id
                )) > 0;
        if ($is_customer_id_exist) {
            wp_send_json_error("customerId exist", 400);
        }
        if (email_exists($email)) {
            $user_id = get_user_by_email($email)->ID;
            if (get_transient(PASSWORD_FREE_USER_VALID . $user_id)) {
                update_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID, $password_free_customer_id, true);
                delete_transient(PASSWORD_FREE_USER_VALID . $user_id);
            } else {
                wp_send_json_error("email exists or validation expired", 400);
            }
        } else {
            if (is_multisite()) {
                switch_to_blog($blogId);
                $user_id = $this->registration_actions($email, $password_free_customer_id);
                restore_current_blog();
            } else {
                $user_id = $this->registration_actions($email, $password_free_customer_id);
            }

        }
        $redirect_code = $this->generate_string();
        set_transient(PASSWORD_FREE_REDIRECT_CODE . $user_id, $redirect_code, 300);
        return ["userId" => $user_id,
            "code" => $redirect_code];
    }

    private function registration_actions($email, $password_free_customer_id) {
        $username = $email;
        $password = $this->generate_string();
        $user_id = wp_create_user($username, $password, $email);
        update_user_meta($user_id, 'first_name', 'NoPass');
        update_user_meta($user_id, 'last_name', 'Customer');
        update_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID, $password_free_customer_id, true);
        return $user_id;
    }

    public function password_free_get_redirect_code($request)
    {
        $body = json_decode($request->get_body(), true);
        $user_id = $body["userId"];
        if (get_userdata($user_id) == '') {
            wp_send_json_error("user not found", 404);
        }
        if (get_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID)[0] == '') {
            wp_send_json_error("user is not NoPass customer", 400);
        }
        $session_id = $body[PASSWORD_FREE_AUTH_SESSION_ID_PARAM];
        if ($session_id == '') {
            wp_send_json_error("sessionId is empty", 400);
        }
        $this->password_free_send_verification_request(PASSWORD_FREE_AUTH_VERIFICATION_URL,
            PASSWORD_FREE_AUTH_SESSION_ID_PARAM, $session_id);
        $redirect_code = $this->generate_string();
        set_transient(PASSWORD_FREE_REDIRECT_CODE . $user_id, $redirect_code, 300);
        return ['code' => $redirect_code];
    }

    public function password_free_email_validation($request)
    {
        $body = json_decode($request->get_body(), true);
        $email = $body["email"];
        if (!is_email($email)) {
            wp_send_json_error("incorrect email", 400);
            exit;
        }
        $is_email_exists = false;
        if (email_exists($email)) {
            $is_email_exists = true;
        }
        return ["isEmailExist" => $is_email_exists];
    }

    public function password_free_redirect_user($request)
    {
        $user_id = $request->get_param('userId');
        $operation = $request->get_param('operation');
        $blog_id = $request->get_param('blogId');
        //TODO
        if ($blog_id == '') {
            $blog_id = get_current_blog_id();
        }
        $redirect_code_param = PASSWORD_FREE_REDIRECT_CODE . $user_id;
        $redirect_code = get_transient($redirect_code_param);
        if (($redirect_code == '' && $redirect_code != $request->get_param('code'))
            || $user_id == '' || get_userdata($user_id) == '') {
            $this->password_free_redirect(get_home_url(), PASSWORD_FREE_STATUS_CODE_301);
        } else {
            if (is_multisite()) {
                switch_to_blog($blog_id);
                $url = $this->get_redirect_url($operation, true, $blog_id, $user_id);
                restore_current_blog();
            } else {
                $url = $this->get_redirect_url($operation, false, $blog_id, $user_id);
            }
            wp_set_auth_cookie($user_id);
            delete_transient($redirect_code_param);
            $this->password_free_redirect($url, PASSWORD_FREE_STATUS_CODE_301);
        }
    }

    private function get_redirect_url($operation, $is_multisite, $blog_id, $user_id)
    {
        if ($operation === 'register' && ($register_url = get_option(PASSWORD_FREE_REDIRECT_AFTER_REGISTER_URL)) != '') {
            $url = $register_url;
        } elseif ($operation === 'auth' && ($auth_url = get_option(PASSWORD_FREE_REDIRECT_AFTER_AUTH_URL)) != '') {
            $url = $auth_url;
        } else {
            if ($is_multisite) {
                $is_added_to_site = get_user_meta($user_id, 'np_'.$blog_id.'_capabilities') != '';
                $url = $is_added_to_site ? get_site_url() . '/wp-admin/profile.php' : get_edit_profile_url();
            } else {
                $url = get_edit_profile_url();
            }

        }
        return $url;
    }

    function password_free_redirect($url, $statusCode)
    {
        exit(wp_safe_redirect($url, $statusCode));
    }

    private function generate_string(): string
    {
        $code_length = 14;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $code_length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function password_free_send_verification_request($url, $param_name, $session_id)
    {
        $data = json_encode(array($param_name => $session_id));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code != 200) {
            wp_send_json_error("verification request failed", 500);
        }
    }
}