<?php

if (!defined('ABSPATH')) {
    exit;
}

function password_free_get_current_url(): string
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
    return $url;
}

function password_free_redirect_query_param(): string
{
    return '&redirect_url=' . password_free_get_current_url();
}

function password_free_multi_sites_default_buttons_on(): bool
{
    return in_array(get_network_option(get_main_site_id(), 'registration'), ['all', 'user']);
}

function password_free_add_db_values_to_blog($db_params)
{
    if ($db_params[PASSWORD_FREE_ACTIVATION_ERROR] == '') {
        $synchronization_status = $db_params[PASSWORD_FREE_SYNCHRONIZATION_STATUS];
        update_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API, $db_params[PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API]);
        update_option(PASSWORD_FREE_ID, $db_params[PASSWORD_FREE_ID]);
        update_option(PASSWORD_FREE_TOKEN, $db_params[PASSWORD_FREE_TOKEN]);
        update_option(PASSWORD_FREE_REGISTER_URL, $db_params[PASSWORD_FREE_REGISTER_URL]);
        update_option(PASSWORD_FREE_LOGIN_URL, $db_params[PASSWORD_FREE_LOGIN_URL]);
        update_option(PASSWORD_FREE_INNER_KEY, $db_params[PASSWORD_FREE_INNER_KEY]);
        update_option(PASSWORD_FREE_VERSIONS, $db_params[PASSWORD_FREE_VERSIONS]);
        update_option(PASSWORD_FREE_UPDATE_NOTIFICATION, $db_params[PASSWORD_FREE_UPDATE_NOTIFICATION]);
        update_option(PASSWORD_FREE_SYNCHRONIZATION_ID, $db_params[PASSWORD_FREE_SYNCHRONIZATION_ID]);
        update_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS, $synchronization_status);
        if ($synchronization_status === PASSWORD_FREE_SYNCHRONIZATION_CONST_PROCESSING) {
            update_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP, true);
        } elseif ($synchronization_status === PASSWORD_FREE_SYNCHRONIZATION_CONST_INTERRUPTED || $synchronization_status === PASSWORD_FREE_SYNCHRONIZATION_CONST_FAILED) {
            update_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP, true);
            update_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_BLOCK, true);
        }
        if ($db_params[PASSWORD_FREE_UPDATE_NOTIFICATION] === PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR) {
            $default_buttons = false;
        } else {
            $default_buttons = is_multisite() ? password_free_multi_sites_default_buttons_on() : get_option('users_can_register');
        }
        update_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON, $default_buttons);
        delete_transient(PASSWORD_FREE_ACTIVATION_ERROR);
    } else {
        set_transient(PASSWORD_FREE_ACTIVATION_ERROR, $db_params[PASSWORD_FREE_ACTIVATION_ERROR]);
    }
    password_free_load_customization_arrays(false);
}

function password_free_get_blog_db_params()
{
    $activation_error = get_transient(PASSWORD_FREE_ACTIVATION_ERROR);
    if ($activation_error != '') {
        $arr = [PASSWORD_FREE_ACTIVATION_ERROR => get_option(PASSWORD_FREE_ACTIVATION_ERROR)];
    } else {
        $arr = [
            PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API => get_option(PASSWORD_FREE_TOKEN_FOR_ACCESS_TO_API),
            PASSWORD_FREE_ID => get_option(PASSWORD_FREE_ID),
            PASSWORD_FREE_TOKEN => get_option(PASSWORD_FREE_TOKEN),
            PASSWORD_FREE_REGISTER_URL => get_option(PASSWORD_FREE_REGISTER_URL),
            PASSWORD_FREE_LOGIN_URL => get_option(PASSWORD_FREE_LOGIN_URL),
            PASSWORD_FREE_INNER_KEY => get_option(PASSWORD_FREE_INNER_KEY),
            PASSWORD_FREE_IS_DEFAULT_BUTTON_ON => get_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON),
            PASSWORD_FREE_VERSIONS => get_option(PASSWORD_FREE_VERSIONS),
            PASSWORD_FREE_UPDATE_NOTIFICATION => get_option(PASSWORD_FREE_UPDATE_NOTIFICATION),
            PASSWORD_FREE_SYNCHRONIZATION_STATUS => get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS),
            PASSWORD_FREE_SYNCHRONIZATION_ID => get_option(PASSWORD_FREE_SYNCHRONIZATION_ID)
        ];
    }
    return $arr;
}

function password_free_get_ids_with_active_plugin($blogs)
{
    $blog_ids_with_active_plugin = array();
    foreach ($blogs as $blog) {
        switch_to_blog($blog->blog_id);
        foreach (get_option('active_plugins') as $active_plugin) {
            if ($active_plugin === PASSWORD_FREE_BASENAME) {
                array_push($blog_ids_with_active_plugin, $blog->blog_id);
            }
        }
        restore_current_blog();
    }
    return $blog_ids_with_active_plugin;
}

function password_free_update_img_url() {
    if (strpos(get_option(PASSWORD_FREE_CUSTOMIZATION_LOGO)[0]['img'], PASSWORD_FREE_URL) === false) {
        password_free_load_customization_arrays(true);
    }
}

function password_free_load_customization_arrays($isCreateCSS)
{
    $arr = [
        'register_url' => '',
        'auth_url' => '',
        'is_default_array' => true,
        'alignment_id' => 1,
        'logo_id' => 0,
        'default' => ['background_color' => '#ffffff',
            'corner_radius' => 4,
            'border_thickness' => 1,
            'border_color' => '#0b3b75',
            'text_font' => 'Arial',
            'text_size' => 16,
            'text_style' => ['bold' => true, 'italic' => false, 'underline' => false],
            'text_color' => '#0b3b75'
        ],
        'hover' => ['background_color' => '#0b3B75',
            'corner_radius' => 4,
            'border_thickness' => 1,
            'border_color' => '#0b3B75',
            'text_font' => 'Arial',
            'text_size' => 16,
            'text_style' => ['bold' => true, 'italic' => false, 'underline' => false],
            'text_color' => '#ffffff'
        ]];
    $fonts = ['SourceSansPro' => '\'Source Sans Pro\'',
        'TimesNewRoman' => 'Times New Roman, serif',
        'Georgia' => 'Georgia, serif',
        'Palatino' => 'Palatino, serif',
        'Helvetica' => 'Helvetica, sans-serif',
        'Arial' => 'Arial, sans-serif',
        'ArialBlack' => 'Arial Black, sans-serif',
        'Verdana' => 'Verdana, sans-serif',
        'Tahoma' => 'Tahoma, sans-serif',
        'TrebuchetMS' => 'Trebuchet MS, sans-serif',
        'Impact' => 'Impact, sans-serif',
        'GillSans' => 'Gill Sans, sans-serif',
        'Courier' => 'Courier, monospace',
        'Lucida' => 'Lucida , monospace',
        'Monaco' => 'Monaco , monospace',
        'BradleyHand' => 'Bradley Hand, cursive',
        'BrushScriptMT' => 'Brush Script MT, cursive',
        'Luminari' => 'Luminari, fantasy',
        'ComicSansMS' => 'Comic Sans MS, cursive'];
    $alignment_prefix = 'password-free-customization-settings-alignment-item-';
    $logo_prefix = 'password-free-customization-settings-logo-';
    $alignment_array = [['id' => 0, 'wrapper_id' => $alignment_prefix . 'wrapper-left-id', 'item_id' => $alignment_prefix . 'left-id', 'inner_item_class' => $alignment_prefix . 'inner-left', 'text' => 'Left'],
        ['id' => 1, 'wrapper_id' => $alignment_prefix . 'wrapper-center-id', 'item_id' => $alignment_prefix . 'center-id', 'inner_item_class' => $alignment_prefix . 'inner-center', 'text' => 'Center'],
        ['id' => 2, 'wrapper_id' => $alignment_prefix . 'wrapper-right-id', 'item_id' => $alignment_prefix . 'right-id', 'inner_item_class' => $alignment_prefix . 'inner-right', 'text' => 'Right'],
        ['id' => 3, 'wrapper_id' => $alignment_prefix . 'wrapper-full-id', 'item_id' => $alignment_prefix . 'full-id', 'inner_item_class' => $alignment_prefix . 'inner-full', 'text' => 'Full width']];
    $logo_array = [['id' => 0, 'wrapper_id' => $logo_prefix . 'wrapper-default-id', 'item_id' => $logo_prefix . 'default-id', 'img' => PASSWORD_FREE_URL . 'admin/images/password-free-customization-logo-default.svg', 'text' => 'Default'],
        ['id' => 1, 'wrapper_id' => $logo_prefix . 'wrapper-inverse-id', 'item_id' => $logo_prefix . 'inverse-id', 'img' => PASSWORD_FREE_URL . 'admin/images/password-free-customization-logo-inverse.svg', 'text' => 'Inverse']];
    $text_buttons = [['id' => 0, 'btn_default_id' => 'password-free-default-text-btn-b', 'btn_hover_id' => 'password-free-hover-text-btn-b', 'text' => 'B'],
        ['id' => 1, 'btn_default_id' => 'password-free-default-text-btn-slash', 'btn_hover_id' => 'password-free-hover-text-btn-slash', 'text' => '/'],
        ['id' => 2, 'btn_default_id' => 'password-free-default-text-btn-u', 'btn_hover_id' => 'password-free-hover-text-btn-u', 'text' => 'U']];
    update_option(PASSWORD_FREE_CUSTOMIZATION_DEFAULT, $arr);
    update_option(PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT, $alignment_array);
    update_option(PASSWORD_FREE_CUSTOMIZATION_LOGO, $logo_array);
    update_option(PASSWORD_FREE_CUSTOMIZATION_FONTS, $fonts);
    update_option(PASSWORD_FREE_CUSTOMIZATION_BUTTONS, $text_buttons);
    if ($isCreateCSS) {
        $data = Password_Free_Buttons_Style::password_free_get_css_data($arr, '', '');
        $blog_id = is_multisite() ? get_current_blog_id() : '';
        file_put_contents(PASSWORD_FREE_PATH . 'admin/css/buttons/password-free-button-style' . $blog_id . '.css', $data, LOCK_EX);
    }
}
