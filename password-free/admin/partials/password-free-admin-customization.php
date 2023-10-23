<?php
password_free_update_img_url();
$alignment_array = get_option(PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT);
if ($alignment_array == '') {
    password_free_load_customization_arrays(false);
    $alignment_array = get_option(PASSWORD_FREE_CUSTOMIZATION_ALIGNMENT);
}
$logo_array = get_option(PASSWORD_FREE_CUSTOMIZATION_LOGO);
$fonts = get_option(PASSWORD_FREE_CUSTOMIZATION_FONTS);
$text_buttons = get_option(PASSWORD_FREE_CUSTOMIZATION_BUTTONS);
$custom_array = get_option(PASSWORD_FREE_CUSTOMIZATION_CUSTOM);
$layout_array = $custom_array == '' ? get_option(PASSWORD_FREE_CUSTOMIZATION_DEFAULT) : $custom_array;
Password_Free_Header::get_password_free_header();
wp_enqueue_style("password_free_customization", PASSWORD_FREE_URL . 'admin/css/password-free-customization.css');
wp_enqueue_style("password_free_button", PASSWORD_FREE_URL . 'admin/css/buttons/password-free-button.css');
?>
<div id="password-free-page-wrapper">
    <div class="password-free-customization-wrapper" id="password-free-customization-wrapper_id">
        <?php
        $update_notification = get_option(PASSWORD_FREE_UPDATE_NOTIFICATION);
        if ($update_notification === PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR && get_transient(PASSWORD_FREE_ACTIVATION_ERROR) == '') {
            Password_Free_Notification_Update::password_free_notification_update_error();
        } elseif ($update_notification === PASSWORD_FREE_UPDATE_NOTIFICATION_WARNING) {
            Password_Free_Notification_Update::password_free_notification_update_warning();
        }
        ?>
        <div class="password-free-customization-title">Customization</div>
        <div class="password-free-customization-text">PasswordFree™ buttons on your site</div>
        <div class="password-free-customization-setting-wrapper">
            <div class="password-free-customization-settings">
                <div class="password-free-customization-settings-alignment">
                    <div class="password-free-customization-settings-alignment-text">Alignment</div>
                    <div class="password-free-customization-settings-alignment-items">
                        <?php
                        foreach ($alignment_array as $item) {
                            ?>
                            <div class="password-free-customization-settings-alignment-item-wrapper"
                                 id="<?php echo $item['wrapper_id'] ?>">
                                <div class="password-free-customization-settings-alignment-item"
                                     id="<?php echo $item['item_id'] ?>"
                                     onclick="alignmentItems(<?php echo htmlspecialchars(json_encode($alignment_array)) ?>, <?php echo $item['id'] ?>, false)">
                                    <div class="<?php echo $item['inner_item_class'] ?>"></div>
                                    <div class="password-free-customization-settings-alignment-item-inner"></div>
                                </div>
                                <div class="password-free-customization-settings-alignment-item-text"><?php echo $item['text'] ?></div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="password-free-customization-settings-logo">
                    <div class="password-free-customization-settings-logo-text">PasswordFree logo</div>
                    <div class="password-free-customization-settings-logo-items">
                        <?php
                        foreach ($logo_array as $item) {
                            ?>
                            <div class="password-free-customization-settings-logo-item-wrapper"
                                 id="<?php echo $item['wrapper_id'] ?>">
                                <div class="password-free-customization-settings-logo-item"
                                     id="<?php echo $item['item_id'] ?>"
                                     onclick="logoItems(<?php echo htmlspecialchars(json_encode($logo_array)) ?>, <?php echo $item['id'] ?>, false)">
                                    <img src="<?php echo $item['img'] ?>"/>
                                </div>
                                <div class="password-free-customization-settings-logo-item-text"><?php echo $item['text'] ?></div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="password-free-customization-settings-default">
                    <div class="password-free-customization-settings-default-title">Default state</div>
                    <div class="password-free-customization-settings-default-background">
                        <div class="password-free-default-text">Background color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-default-background-color-input-id"></label>
                            <input type="text" id="password-free-default-background-color-input-id"
                                   onchange="defaultBackgroundColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-default-background-color-id"></label>
                            <input type="color" id="password-free-default-background-color-id"
                                   onchange="defaultBackgroundColor(this)"/>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-corner">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Corner radius
                        </div>
                        <label for="password-free-default-corner-input-id"></label>
                        <input type="number" id="password-free-default-corner-input-id" min="0"
                               max="1000" step="1" oninput="defaultCornerRadius(this)"
                               onchange="defaultCornerRadiusChange(this)">
                    </div>
                    <div class="password-free-customization-settings-default-border">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Border thickness
                        </div>
                        <label for="password-free-default-border-input-id"></label>
                        <input type="number" id="password-free-default-border-input-id" min="0"
                               max="1000" step="1" oninput="defaultBorderThickness(this)"
                               onchange="defaultBorderThicknessChange(this)">
                    </div>
                    <div class="password-free-customization-settings-default-border-color">
                        <div class="password-free-default-text">Border color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-default-border-color-input-id"></label>
                            <input type="text" id="password-free-default-border-color-input-id"
                                   onchange="defaultBorderColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-default-border-color-id"></label>
                            <input type="color" id="password-free-default-border-color-id"
                                   onchange="defaultBorderColor(this)"/>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-text-font">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Text font
                        </div>
                        <div class="password-free-default-text-context">
                            <label for="password-free-default-font"></label>
                            <select id="password-free-default-font" onchange="defaultTextFont(this)">
                                <?php
                                foreach ($fonts as $key => $val) {
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                                <?php } ?>
                            </select>
                            <label for="password-free-default-text-size-input-id"></label>
                            <input type="number" id="password-free-default-text-size-input-id" min="1"
                                   max="1000" step="1" oninput="defaultTextSize(this)"
                                   onchange="defaultTextSizeChange(this)">
                            <?php
                            foreach ($text_buttons as $button) {
                                ?>
                                <div class="password-free-default-text-btn"
                                     id="<?php echo $button['btn_default_id'] ?>"
                                     onclick="changeDefaultTextStyle('<?php echo $button['id'] ?>', '<?php echo $button['btn_default_id'] ?>')">
                                    <?php echo $button['text'] ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-text-color">
                        <div class="password-free-default-text">Text color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-default-text-color-input-id"></label>
                            <input type="text" id="password-free-default-text-color-input-id"
                                   onchange="defaultTextColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-default-text-color-id"></label>
                            <input type="color" id="password-free-default-text-color-id"
                                   onchange="defaultTextColor(this)"/>
                        </div>
                    </div>
                </div>
                <div class="password-free-customization-settings-default">
                    <div class="password-free-customization-settings-default-title">Hover state</div>
                    <div class="password-free-customization-settings-default-background">
                        <div class="password-free-default-text">Background color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-hover-background-color-input-id"></label>
                            <input type="text" id="password-free-hover-background-color-input-id"
                                   onchange="hoverBackgroundColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-hover-background-color-id"></label>
                            <input type="color" id="password-free-hover-background-color-id"
                                   onchange="hoverBackgroundColor(this)"/>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-corner">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Corner radius
                        </div>
                        <label for="password-free-hover-corner-input-id"></label>
                        <input type="number" id="password-free-hover-corner-input-id" min="0"
                               max="1000" step="1" oninput="hoverCornerRadius(this)"
                               onchange="hoverCornerRadiusChange(this)">
                    </div>
                    <div class="password-free-customization-settings-default-border">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Border thickness
                        </div>
                        <label for="password-free-hover-border-input-id"></label>
                        <input type="number" id="password-free-hover-border-input-id" min="0"
                               max="1000" step="1" oninput="hoverBorderThickness(this)"
                               onchange="hoverBorderThicknessChange(this)">
                    </div>
                    <div class="password-free-customization-settings-default-border-color">
                        <div class="password-free-default-text">Border color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-hover-border-color-input-id"></label>
                            <input type="text" id="password-free-hover-border-color-input-id"
                                   onchange="hoverBorderColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-hover-border-color-id"></label>
                            <input type="color" id="password-free-hover-border-color-id"
                                   onchange="hoverBorderColor(this)"/>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-text-font">
                        <div class="password-free-default-text password-free-default-text-mg">
                            Text font
                        </div>
                        <div class="password-free-default-text-context">
                            <label for="password-free-hover-font"></label>
                            <select id="password-free-hover-font" onchange="hoverTextFont(this)">
                                <?php
                                foreach ($fonts as $key => $val) {
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $key ?></option>
                                <?php } ?>
                            </select>
                            <label for="password-free-hover-text-size-input-id"></label>
                            <input type="number" id="password-free-hover-text-size-input-id" min="1"
                                   max="1000" step="1" oninput="hoverTextSize(this)"
                                   onchange="hoverTextSizeChange(this)">
                            <?php
                            foreach ($text_buttons as $button) {
                                ?>
                                <div class="password-free-default-text-btn"
                                     id="<?php echo $button['btn_hover_id'] ?>"
                                     onclick="changeHoverTextStyle('<?php echo $button['id'] ?>', '<?php echo $button['btn_hover_id'] ?>')">
                                    <div class="password-free-hover-text-btn-symbol">
                                        <?php echo $button['text'] ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="password-free-customization-settings-default-text-color">
                        <div class="password-free-default-text">Text color</div>
                        <div class="password-free-customization-settings-color">
                            <label for="password-free-hover-text-color-input-id"></label>
                            <input type="text" id="password-free-hover-text-color-input-id"
                                   onchange="hoverTextColorInput(this)" oninput="changeColorFieldSize(this)" maxlength="7">
                            <label for="password-free-hover-text-color-id"></label>
                            <input type="color" id="password-free-hover-text-color-id"
                                   onchange="hoverTextColor(this)"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="password-free-customization-show-buttons">
                <div class="password-free-customization-show-buttons-title">
                    Preview for buttons
                </div>
                <div class="password-free-customization-show-buttons-default-text">
                    Default state
                </div>
                <div class="password-free-customization-show-buttons-default">
                    <?php echo Password_Free_Buttons::password_free_button(true, false, false) ?>
                    <?php echo Password_Free_Buttons::password_free_button(false, false, false) ?>
                </div>
                <div class="password-free-customization-show-buttons-hover-text">
                    Hover state
                </div>
                <div class="password-free-customization-show-buttons-default">
                    <div class="password-free-button-hover">
                        <img src="" alt="img">
                        <div>Sign up PasswordFree</div>
                    </div>
                    <div class="password-free-button-hover">
                        <img src="" alt="img">
                        <div>Sign in PasswordFree</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="password-free-customization-redirect-title">Redirect links</div>
        <div class="password-free-customization-redirect-settings">
            <div class="password-free-customization-redirect-text">
                You can define links to which users will be redirected after registration and authentication. <span>By default, users will be redirected to the profile page.</span>
            </div>
            <div class="password-free-customization-redirect-block">
                <div class="password-free-customization-redirect-block-text">
                    Post-registration URL
                </div>
                <label>
                    <input type="text" onchange="changeRegisterUrl(this)" id="password-free-url-change-register">
                </label>
            </div>
            <div class="password-free-customization-redirect-block-error"
                 id="password-free-customization-register-error-id">
                This URL is invalid. Please try again.
            </div>
            <div class="password-free-customization-redirect-block password-free-customization-mg-5">
                <div class="password-free-customization-redirect-block-text">
                    Post-authentication URL
                </div>
                <label>
                    <input type="text" onchange="changeAuthUrl(this)" id="password-free-url-change-auth">
                </label>
            </div>
            <div class="password-free-customization-redirect-block-error"
                 id="password-free-customization-auth-error-id">
                This URL is invalid. Please try again.
            </div>
        </div>
        <div class="password-free-customization-btn">
            <button class="password-free-customization-btn-apply" id="password-free-customization-btn-apply-id"
                    onclick="pfApplyChanges('<?php echo PASSWORD_FREE_CUSTOMIZATION_APPLY_URL ?>', '<?php echo PASSWORD_FREE_CUSTOMIZATION_APPLY_DEFAULT_URL ?>','<?php echo get_option(PASSWORD_FREE_INNER_KEY) ?>')">
                Apply
            </button>
            <button class="password-free-customization-btn-default" id="password-free-customization-btn-default-id"
                    onclick="pfDefaultSettings('<?php echo PASSWORD_FREE_CUSTOMIZATION_DEFAULT_URL ?>', '<?php echo get_option(PASSWORD_FREE_INNER_KEY) ?>')">
                Use Default settings
            </button>
        </div>
        <div class="password-free-customization-popup-apply" id="password-free-customization-popup-accept-apply">
            <div class="password-free-customization-popup-apply-text">Settings applied</div>
            <img onclick="pfCloseAcceptApplyPopUp()"
                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-close-btn.svg' ?>">
        </div>
        <div class="password-free-customization-popup-apply" id="password-free-customization-popup-accept-default">
            <div class="password-free-customization-popup-apply-text">Click Apply to accept the Default settings
            </div>
            <img onclick="pfCloseAcceptDefaultPopUp()"
                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-close-btn.svg' ?>">
        </div>
        <?php echo '<script>fillData(' . json_encode($layout_array) . ',
         ' . json_encode($alignment_array) . ', ' . json_encode($logo_array) . ',
          ' . json_encode($text_buttons) . ', ' . json_encode($fonts) . ');</script>' ?>
    </div>
</div>
<div class="password-free-customization-change-popup" id="password-free-customization-change-popup-id">
    <div class="password-free-customization-change-popup-title">You have unsaved changes</div>
    <div class="password-free-customization-change-popup-text">
        You can stay on the page and save the changes later by clicking on the Apply button or leave the page without saving. What do you want to do?
    </div>
    <div class="password-free-customization-change-popup-buttons">
        <button class="password-free-customization-change-popup-stay" onclick="changePopUpStay()">Stay on page
        </button>
        <button class="password-free-customization-change-popup-leave" onclick="changePopUpLeave()">Leave page
        </button>
    </div>
</div>
<div id="password-free-customization-overlay"></div>