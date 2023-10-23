<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Notification
{

    public function password_free_notify_user_deleted($user_id)
    {
        if ($this->password_free_is_customer($user_id)) {
            $customer_id = get_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID)[0];
            $ch = curl_init(PASSWORD_FREE_DELETE_CUSTOMER_URL . $customer_id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    public function password_free_notify_site_email_updated()
    {
        $this->password_free_update_site_info_notification();
    }

    public function password_free_notify_user_email_updated($user_id, $old_user_data)
    {
        $old_user_email = $old_user_data->data->user_email;
        $new_user_email = get_userdata($user_id)->user_email;
        if ($new_user_email === get_option('admin_email')) {
            $this->password_free_update_site_info_notification();
        } else if ($new_user_email !== $old_user_email && $this->password_free_is_customer($user_id)) {
            $costumer_id = get_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID)[0];
            $this->password_free_update_email_notification($costumer_id);
        }
    }

    public function password_free_notify_pre_delete_site($errors, $old_site) {
        if (is_multisite() && empty($errors->errors)) {
            $old_blog_id = $old_site->blog_id;
            switch_to_blog($old_blog_id);
            $is_main_url = get_option(PASSWORD_FREE_IS_MAIN_URL);
            restore_current_blog();
            if ($is_main_url) {
                $blogs = get_sites();
                $blog_ids_with_active_plugin = password_free_get_ids_with_active_plugin($blogs);
                if (count($blog_ids_with_active_plugin) == 1) {
                        switch_to_blog(get_main_site_id());
                        $site_url = get_site_url();
                        $this->send_change_url_request($site_url);
                        restore_current_blog();
                } else {
                    for ($i = 0; $i < count($blog_ids_with_active_plugin); $i++) {
                        if ($blog_ids_with_active_plugin[$i] != $old_blog_id) {
                            switch_to_blog($blog_ids_with_active_plugin[$i]);
                            $site_url = get_site_url();
                            $this->send_change_url_request($site_url);
                            update_option(PASSWORD_FREE_IS_MAIN_URL, true);
                            restore_current_blog();
                            break;
                        }
                    }

                }
            }
        }
    }

    public function password_free_notify_url_updated($old_value, $value)
    {
            $this->send_change_url_request($value);
    }

    public function send_change_url_request($value) {
        $data = json_encode(array('url' => $value));
        $ch = curl_init(PASSWORD_FREE_UPDATE_SITE_ADDRESS_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function password_free_is_customer($user_id): bool
    {
        return get_user_meta($user_id, PASSWORD_FREE_CUSTOMER_ID)[0] != '';
    }

    private function password_free_update_email_notification($password_free_customer_id)
    {
        $data = json_encode(array('customerId' => $password_free_customer_id));
        $ch = curl_init(PASSWORD_FREE_UPDATE_EMAIL_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function password_free_update_site_info_notification()
    {
        $ch = curl_init(PASSWORD_FREE_UPDATE_SITE_INFO_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            ['Authorization:' . get_option(PASSWORD_FREE_TOKEN), 'Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);
    }
}
