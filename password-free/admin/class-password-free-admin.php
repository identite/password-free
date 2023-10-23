<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Admin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function password_free_add_admin_menu()
    {
        $red_dote_img_string = '<img class="password-free-admin-menu-red-dot" src="'.PASSWORD_FREE_URL . 'admin/images/password-free-red-dot.svg'.'"></img>';
        $red_dote_img = (get_option(PASSWORD_FREE_UPDATE_NOTIFICATION) != '' || get_transient(PASSWORD_FREE_ACTIVATION_ERROR) || PASSWORD_FREE_IS_SYNC_STATUS_INCORRECT) ? $red_dote_img_string : '';
        add_menu_page(
            __('PasswordFree Main Page', 'password-free'),
            '<span>PasswordFree</span>'. $red_dote_img,
            'manage_options',
            'password-free-overview',
            '',
            plugins_url('admin/images/password-free-logo.svg', __DIR__),
            100
        );

        add_submenu_page(
            'password-free-overview',
            'PasswordFree Overview Page',
            'Overview',
            'manage_options',
            'password-free-overview',
            [$this, 'password_free_admin_overview_page']
        );

        add_submenu_page(
            'password-free-overview',
            'PasswordFree Customization Page',
            'Customization',
            'manage_options',
            'password-free-customization',
            [$this, 'password_free_admin_customization_page']
        );

        add_submenu_page(
            'password-free-overview',
            'PasswordFree Support Page',
            'Support',
            'manage_options',
            'password-free-support',
            [$this, 'password_free_admin_menu_support']
        );
    }

    public function password_free_admin_overview_page()
    {
        require_once PASSWORD_FREE_PATH . 'admin/partials/password-free-admin-overview.php';
    }

    public function password_free_admin_customization_page()
    {
        require_once PASSWORD_FREE_PATH . 'admin/partials/password-free-admin-customization.php';
    }

    public function password_free_admin_menu_support()
    {
        require_once PASSWORD_FREE_PATH . 'admin/partials/password-free-admin-support.php';
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/password-free-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/password-free-admin.js', array('jquery'), $this->version, false);
        if ($GLOBALS['pagenow'] === 'admin.php' && @ $_REQUEST['page'] === 'password-free-customization') {
            wp_enqueue_script("password-free-customization-js", PASSWORD_FREE_URL . 'admin/js/password-free-customization.js');
        }
    }
}
