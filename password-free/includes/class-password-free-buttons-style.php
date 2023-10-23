<?php

if (!defined('ABSPATH')) {
    exit;
}

class Password_Free_Buttons_Style
{
    public static function password_free_get_css_data($array, $width, $height): string
    {
        $logoArray = get_option(PASSWORD_FREE_CUSTOMIZATION_LOGO);
        $fontArray = get_option(PASSWORD_FREE_CUSTOMIZATION_FONTS);
        $logo_img = '';
        foreach ($logoArray as $logo) {
            if ($logo['id'] === $array['logo_id']) {
                $logo_img = $logo['img'];
            }
        }
        $defaultArray = $array['default'];
        $hoverArray = $array['hover'];
        $defaultTextStyle = $defaultArray['text_style'];
        $hoverTextStyle = $hoverArray['text_style'];
        $alignment = '';

        $widthStyle = '';
        $login_pag_settings = '';
        $width_login_form = '';
        if (($width != '' && $width <= 270) || $array['is_default_array']) {
            switch ($array['alignment_id']) {
                case 0:
                    $alignment = 'text-align: left;';
                    break;
                case 1:
                    $alignment = 'text-align: center;';
                    break;
                case 2:
                    $alignment = 'text-align: right;';
                    break;
                case 3:
                    $widthStyle = 'width: 270px';
                    $alignment = 'text-align: center;';
                    break;
            }
        } elseif ($width != '' && $width > 270) {
            $width_login_form = 'width: ' . ($width  + 51) . 'px;';
            $login_pag_settings =
                'display: flex;
                justify-content: center;';
            $alignment = 'align-items: center;';

        }


        $fonts = '
            @font-face {
                font-weight: 300;
                font-family: \'Source Sans Pro\';
                src: url("../../fonts/SourceSansPro-Light.ttf");
            }
            @font-face {
                font-weight: 400;
                font-family: \'Source Sans Pro\';
                src: url("../../fonts/SourceSansPro-Regular.ttf");
            }
            @font-face {
                font-weight: 600;
                font-family: \'Source Sans Pro\';
                src: url("../../fonts/SourceSansPro-Semibold.ttf");
            }';

        return $fonts . '
        .password-free-button img {
            content: url("' . $logo_img . '");
        }
        .password-free-button {
            cursor: pointer;
            user-select: none;
            background: ' . $defaultArray['background_color'] . ';
            border-radius: ' . $defaultArray['corner_radius'] . 'px;
            border: ' . $defaultArray['border_thickness'] . 'px solid ' . $defaultArray['border_color'] . ';
            font-family: ' . $fontArray[$defaultArray['text_font']] . ';
            font-size: ' . $defaultArray['text_size'] . 'px;
            font-weight: ' . ($defaultTextStyle['bold'] ? 600 : 300) . ';
            font-style: ' . ($defaultTextStyle['italic'] ? 'italic' : 'normal') . ';
            text-decoration: ' . ($defaultTextStyle['underline'] ? 'underline' : 'none') . ';
            color: ' . $defaultArray['text_color'] . ';
        }
        .password-free-button:hover {
            background: ' . $hoverArray['background_color'] . ';
            border-radius: ' . $hoverArray['corner_radius'] . 'px;
            border: ' . $hoverArray['border_thickness'] . 'px solid ' . $hoverArray['border_color'] . ';
            font-family: ' . $fontArray[$hoverArray['text_font']] . ';
            font-size: ' . $hoverArray['text_size'] . 'px;
            font-weight: ' . ($hoverTextStyle['bold'] ? 600 : 300) . ';
            font-style: ' . ($hoverTextStyle['italic'] ? 'italic' : 'normal') . ';
            text-decoration: ' . ($hoverTextStyle['underline'] ? 'underline' : 'none') . ';
            color: ' . $hoverArray['text_color'] . ';
        }
        .password-free-login-page-wrapper {
            ' . $alignment . '          
        }
        .password-free-login-page-wrapper .password_free_motivation_text {
            ' . $alignment . '
        }
        .password-free-login-page-wrapper .password-free-button {
            ' . $widthStyle . '
        }
        .password-free-button-part {
            position: relative;
        }
        #login {
            ' . $width_login_form . '
        }
        .password-free-login-page-wrapper .password-free-button-part {
            ' . $login_pag_settings . '
        }
        ';
    }
}


