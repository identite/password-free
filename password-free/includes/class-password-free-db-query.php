<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Db_Query
{

    public static function remove_short_codes()
    {
        global $wpdb;
        $wpdb->get_var(
            $wpdb->prepare(
                "UPDATE $wpdb->posts SET post_content = replace(post_content, %s, '') ",
                '[' . PASSWORD_FREE_SHORT_CODE_SIGN_UP . ']'
            ));
        $wpdb->get_var(
            $wpdb->prepare(
                "UPDATE $wpdb->posts SET post_content = replace(post_content, %s, '') ",
                '[' . PASSWORD_FREE_SHORT_CODE_SIGN_IN . ']'
            ));
    }
}

