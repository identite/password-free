<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Header
{

    public static function get_password_free_header()
    {
        wp_enqueue_style("password_free_header", PASSWORD_FREE_URL . 'admin/css/password-free-header.css');
        ?>
        <div class="password-free-header-wrapper">
            <img src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-header-img.svg' ?>"
                 alt="My Happy SVG"/>
        </div>
        <?php
    }
}


