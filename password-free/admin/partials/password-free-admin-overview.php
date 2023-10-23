<?php
if (!defined('ABSPATH')) {
    exit;
}
Password_Free_Header::get_password_free_header();
wp_enqueue_style("password-free-overview", PASSWORD_FREE_URL . 'admin/css/password-free-overview.css');
wp_enqueue_script("password-free-overview-js", PASSWORD_FREE_URL . 'admin/js/password-free-overview.js');
$inner_key = get_option(PASSWORD_FREE_INNER_KEY);
$sync_status = get_option(PASSWORD_FREE_SYNCHRONIZATION_STATUS);
$is_sync_status_correct = $sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_SUCCESSFUL || $sync_status == PASSWORD_FREE_SYNCHRONIZATION_CONST_NOT_REQUIRED;
$is_user_can_register = get_option('users_can_register');
$is_update_error = get_option(PASSWORD_FREE_UPDATE_NOTIFICATION) === PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR;
$show_register_error_block = $is_user_can_register ? 'none' : 'block';
$disable_default_buttons = $is_user_can_register && !$is_update_error && $is_sync_status_correct ? 'auto' : 'none';
$is_default_buttons_on = get_option(PASSWORD_FREE_IS_DEFAULT_BUTTON_ON) && $is_sync_status_correct;
?>
    <div id="password-free-page-wrapper">
        <?php
        if (get_transient(PASSWORD_FREE_ACTIVATION_ERROR) == '') {
            $array = array(
                'key' => $inner_key,
                'showProcessingNotice' => get_option(PASSWORD_FREE_SYNCHRONIZATION_PROCESS_SHORT_POPUP),
                'showSyncFailPopup' => get_option(PASSWORD_FREE_SYNCHRONIZATION_FAIL_POPUP),
                'showSyncPopup' => get_option(PASSWORD_FREE_SYNCHRONIZATION_START_POPUP),
                'showActivationPopup' => get_option(PASSWORD_FREE_IS_ACTIVATED_FOR_POPUP),
                'syncStatusUrl' => PASSWORD_FREE_CLOSE_SYNCHRONIZATION_GET_STATUS_URL,
                'syncStatus' => $sync_status
            );
            wp_localize_script('password-free-overview-js', 'overviewVariables', $array);
        }
        ?>
        <div id="password-free-overview-overlay"></div>
        <div class="password-free-overview-wrapper" id="password-free-overview-wrapper-id">
            <div class="password-free-overview-title">Overview</div>
            <div class="password-free-overview-content">
                <img class="password-free-overview-context-img"
                     src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-logo-full.svg' ?>"
                     alt="PasswordFree Logo"/>
                <div class="password-free-overview-text-wrapper">
                    <div class="password-free-overview-text">
                        The PasswordFree™ plugin is a tool for passwordless authentication and registration that uses
                        patented Full-Duplex
                        Authentication to authenticate users coming into a customer portal. Using the PasswordFree
                        Authentication™ and registration options, your customers can get simple and secure access to
                        their accounts
                        with your webstore.
                    </div>
                </div>
            </div>
            <?php
            if (get_transient(PASSWORD_FREE_ACTIVATION_ERROR)) {
                ?>
                <div class="password-free-overview-error">
                    <div class="password-free-error-text">Installation error</div>
                    <div class="password-free-reinstall-text">Reinstall the PasswordFree™ application.</div>
                </div>
                <?php
            } else {
                ?>
                <div class="password-free-overview-buttons">
                    <div class="password-free-overview-user-disable-register-block"
                         style="display: <?php echo $show_register_error_block ?>">
                        <div class="password-free-overview-user-disable-register-block-title">
                            Your default User Registration is turned off
                        </div>
                        <div class="password-free-overview-user-disable-register-block-text">
                            Turn on the User Registration on your website for PasswordFree™ to work correctly.
                            Then the PasswordFree™ buttons will be available to display.
                        </div>
                        <img class="password-free-overview-default-button-help-btn-error"
                             onmouseover="openHelpPopUpRegisterError()" onmouseout="closeHelpPopUpRegisterError()"
                             src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-overview-help-error.svg' ?>"/>
                        <div id="password-free-overview-popup-help-register-error">
                            <div class="password-free-overview-popup-help-error-title">
                                Enabling User Registration
                            </div>
                            <div class="password-free-overview-popup-help-error-context">
                                Go to the <span>Settings >> General</span> page in your admin area, scroll down to the
                                <span>Membership</span> section, and check the box next to
                                <span>Anyone can register</span> option.
                            </div>
                            <img class="password-free-overview-help-popup-error-img"
                                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-help-popup-error-img.svg' ?>">
                        </div>
                    </div>
                    <div class="password-free-synchronization-fail-block"
                         id="password-free-synchronization-fail-block-id">
                        <div class="password-free-synchronization-fail-block-title">
                            User database needs to be updated
                        </div>
                        <div class="password-free-synchronization-fail-block-text">
                            The PasswordFree™ buttons will be unavailable for users until the database is updated.
                            You can submit a request to <a
                                    href="https://nopass.atlassian.net/servicedesk/customer/portal/2/group/11/create/29"
                                    target="_blank">PasswordFree™ Support</a> to solve this issue.
                        </div>
                    </div>
                    <div class="password-free-overview-buttons-title">
                        To get started, add PasswordFree™ buttons to your website.
                    </div>
                    <div class="password-free-overview-default-button" id="password-free-overview-default-button-id"
                         style="pointer-events: <?php echo $disable_default_buttons ?>">
                        <div class="password-free-overview-toggle-wrapper">
                            <input class="password-free-overview-toggle-input" type="checkbox"
                                <?php if ($is_default_buttons_on) {
                                    ?> checked="checked"  <?php } ?>
                                   id="password-free-overview-switch"/>
                            <label class="password-free-overview-toggle-label" for="password-free-overview-switch"
                                   id="password-free-overview-label"
                                   onclick="toggleClick('<?php echo PASSWORD_FREE_DEFAULT_BUTTON_URL ?>', '<?php echo $inner_key ?>')">Toggle</label>
                        </div>
                        <div class="password-free-overview-default-button-text">
                            <div class="password-free-overview-default-button-title">Display PasswordFree™ buttons by
                                default
                            </div>
                            <img class="password-free-overview-default-button-help-btn" onmouseover="openHelpPopUp()"
                                 onmouseout="closeHelpPopUp()"
                                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-overview-help.svg' ?>"/>
                            <div class="password-free-overview-default-button-info">By default, the buttons are located
                                above your Sign in and Sign up forms.
                            </div>
                            <div id="password-free-overview-popup-help">
                                <div class="password-free-overview-popup-help-title">
                                    Default PasswordFree™ buttons location
                                </div>
                                <div class="password-free-overview-popup-help-context">
                                    By default, the buttons are located above your Sign in and Sign up forms.
                                </div>
                                <div class="password-free-overview-help-popup-img">
                                    <img src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-help-popup-img.svg' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="password-free-overview-dropdown-wrapper">
                        <div class="password-free-overview-dropdown-text">To customize the placement of the buttons in
                            your site,&nbsp
                        </div>
                        <div class="password-free-overview-dropdown-btn" onclick="showDropdown()"> use Shortcode</div>
                        <img class="password-free-overview-dropdown-arrow" id="password-free-overview-dropdown-arrow-id"
                             onclick="showDropdown()"
                             src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-arrow.svg' ?>"/>
                    </div>
                    <div class="password-free-overview-dropdown-context"
                         id="password-free-overview-dropdown-context-id">
                        <div class="password-free-overview-buttons-text">
                            Add the button shortcode in the place where you want the button to appear. For more details,
                            read the
                            <a href="https://docs.nopass.us/user-guide/User_guides/End-user_guide/WordPress_integration/Developer_guide/Install_and_activate_PasswordFree_plugin.htm" target="_blank">WordPress
                                Developer Guide.</a>
                        </div>
                        <div class="password-free-overview-buttons-context">
                            <div class="password-free-overview-buttons-context-button">Button</div>
                            <div class="password-free-overview-buttons-context-short-code">ShortCode</div>
                        </div>
                        <div class="password-free-overview-buttons-part">
                            <div class="password-free-overview-buttons-button">
                                <img class="password-free-overview-buttons-button-auth-btn"
                                     src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-auth-button-img.svg' ?>">
                                <span>Sign up PasswordFree </span>
                            </div>
                            <div class="password-free-overview-buttons-short-code">
                                [<?php echo PASSWORD_FREE_SHORT_CODE_SIGN_UP ?>]
                            </div>
                            <img class="password-free-overview-copy-btn"
                                 onclick="copyText('[<?php echo PASSWORD_FREE_SHORT_CODE_SIGN_UP ?>]')"
                                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-copy-button.svg' ?>">
                        </div>
                        <div class="password-free-overview-buttons-part">
                            <div class="password-free-overview-buttons-button">
                                <img class="password-free-overview-buttons-button-auth-btn"
                                     src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-auth-button-img.svg' ?>">
                                <span>Sign in PasswordFree</span>
                            </div>
                            <div class="password-free-overview-buttons-short-code">
                                [<?php echo PASSWORD_FREE_SHORT_CODE_SIGN_IN ?>]
                            </div>
                            <img class="password-free-overview-copy-btn"
                                 onclick="copyText('[<?php echo PASSWORD_FREE_SHORT_CODE_SIGN_IN ?>]')"
                                 src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-copy-button.svg' ?>">
                        </div>
                    </div>
                    <div class="password-free-overview-deactivation-checkbox">
                        <div class="password-free-overview-checkbox-container">
                            <input class="password-free-overview-checkbox-input" type="checkbox"
                                   id="password-free-overview-checkbox"
                                <?php if (get_option(PASSWORD_FREE_SHORTCODE_DEACTIVATION_REMOVE)) {
                                    ?> checked="checked"  <?php } ?>/>
                            <label class="password-free-overview-checkbox-label" for="password-free-overview-checkbox"
                                   onclick="checkboxClick('<?php echo PASSWORD_FREE_SHORT_CODE_DEACTIVATION_REMOVE_URL ?>', '<?php echo $inner_key ?>')">
                            </label>
                        </div>
                        <div class="password-free-overview-checkbox-title">
                            Remove custom buttons in case of PasswordFree™ deactivation
                        </div>
                        <div class="password-free-overview-checkbox-text">
                            If you don’t check the box, the [passwordfree_sign_up/in] code will be displayed instead of
                            the custom buttons after PasswordFree™ deactivation.
                        </div>
                    </div>
                    <?php
                    $update_notification = get_option(PASSWORD_FREE_UPDATE_NOTIFICATION);
                    if ($update_notification === PASSWORD_FREE_UPDATE_NOTIFICATION_ERROR) {
                        Password_Free_Notification_Update::password_free_notification_update_error();
                    } elseif ($update_notification === PASSWORD_FREE_UPDATE_NOTIFICATION_WARNING) {
                        Password_Free_Notification_Update::password_free_notification_update_warning();
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <div class="password-free-overview-short-popup-class" id="password-free-overview-popup-copy">
                <div class="password-free-overview-short-popup-text">Shortcode copied</div>
                <img onclick="closeCopyShortPopup()"
                     src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-close-btn.svg' ?>">
            </div>
            <div class="password-free-overview-short-popup-class"
                 id="password-free-overview-synchronization-process-popup">
                <div class="password-free-overview-short-popup-text">Please wait: User Database synchronization</div>
            </div>
            <div class="password-free-overview-short-popup-class"
                 id="password-free-overview-synchronization-success-popup">
                <div class="password-free-overview-short-popup-text">User Database synchronized</div>
                <img onclick="closeSuccessShortPopup()"
                     src="<?php echo PASSWORD_FREE_URL . 'admin/images/password-free-close-btn.svg' ?>">
            </div>
            <!--            --><?php //echo '<script>showSyncSuccessPopup();</script>' ?>
        </div>
        <div class="password-free-synchronization-fail-popup" id="password-free-synchronization-fail-popup-id">
            <div class="password-free-synchronization-fail-popup-title">
                User database updating failed
            </div>
            <div class="password-free-synchronization-fail-popup-text">
                Something went wrong. The PasswordFree™ buttons will be unavailable for users until the database is
                updated.<br>
                You can submit a request to PasswordFree™ Support to solve<br> this issue.
            </div>
            <div class="password-free-synchronization-fail-popup-buttons">
                <button class="password-free-synchronization-fail-popup-button-support"
                        onclick="redirectSynchronizationFailPopup()">
                    Go to Support
                </button>
                <button class="password-free-synchronization-fail-popup-button-close"
                        onclick="closeSynchronizationFailPopup('<?php echo PASSWORD_FREE_CLOSE_SYNCHRONIZATION_FAIL_POPUP_URL ?>')">
                    Close
                </button>
            </div>
        </div>
        <div class="password-free-synchronization-popup" id="password-free-synchronization-popup-id">
            <div class="password-free-synchronization-popup-title">
                User database needs to be updated
            </div>
            <div class="password-free-synchronization-popup-text">
                The PasswordFree™ buttons will be unavailable for users until the database is updated. Please wait a
                little.
            </div>
            <button onclick="closeSynchronizationPopup('<?php echo PASSWORD_FREE_CLOSE_SYNCHRONIZATION_POPUP_URL ?>', '<?php echo get_option(PASSWORD_FREE_INNER_KEY) ?>')">
                Got it
            </button>
        </div>
        <div class="password-free-activation-popup" id="password-free-activation-popup-id">
            <div class="password-free-activation-popup-header">
                Congratulations!<br>
                You have successfully installed the PasswordFree™ app!
            </div>
            <div class="password-free-activation-popup-content">
                <span>Now, the following options are available to you:</span>
                <div class="password-free-activation-popup-ul">
                    <ul>
                        <li>Providing your customers with PasswordFree Authentication™ and registration.</li>
                        <li>Managing your customers.</li>
                        <li>Customizing the PasswordFree™ forms and buttons.</li>
                        <li>Reaching out to the PasswordFree™ support team with your queries and issues.</li>
                    </ul>
                </div>

                <br>
                <span>To see the new PasswordFree™ options, click View Store in your Control Panel.</span>
                The new options are available on the registration and authentication pages of your webstore.
            </div>
            <button type="button" class="password-free-activation-btn-cancel"
                    onclick="closeActivationPopup('<?php echo PASSWORD_FREE_CLOSE_ACTIVATION_POPUP_URL ?>')">
                Got it
            </button>
        </div>
    </div>

<?php

