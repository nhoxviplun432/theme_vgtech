<?php

/**
 * Register Form
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
    <?php do_action('woocommerce_before_customer_login_form'); ?>
    <div class="div-wrap-form-register form-register-area">
        <div class="col-2 col-form-register">
            <h2 class="title-register"><?php esc_html_e('Đăng ký', 'woocommerce'); ?></h2>
            <p style="display: none">Already have an account?<a style="color:#1d78d0;" href="<?php echo home_url() ?>/my-account"> Log In</a> now.</p>
            <form method="post" enctype="multipart/form-data" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <div class="woocommerce-form-row row-first-last-name woocommerce-form-row--wide form-row form-row-wide">
                    <div class="input-wrapper">
                        <label for="first_name"><?php _e('Họ', 'text-domain'); ?><span class="required">*</span></label>
                        <input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php if (isset($_POST['account_first_name'])) echo esc_attr($_POST['account_first_name']); ?>" required />
                    </div>
                    <div class="input-wrapper" style="margin-top: 10px">
                        <label for="last_name"><?php _e('Tên', 'text-domain'); ?><span class="required">*</span></label>
                        <input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php if (isset($_POST['account_last_name'])) echo esc_attr($_POST['account_last_name']); ?>" required />
                    </div>
                </div>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e('Tên đăng nhập', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine 
                                                                                                                                                                                                                                                                        ?>
                    </p>

                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_email"><?php esc_html_e('Địa chỉ email', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" placeholder="" /><?php // @codingStandardsIgnoreLine 
                                                                                                                                                                                                                                                                        ?>
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Mật khẩu', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="Vui lòng nhập 8 ký tự trở lên với các ký tự đặc biệt" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password_2"><?php _e('Xác nhận mật khẩu', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="confirm_password" id="confirm_password" autocomplete="new-password" />
                    </p>
                <?php else : ?>

                    <p style="font-size: 14px; margin-left: 4px;"><?php esc_html_e('A password will be sent to your email address.', 'woocommerce'); ?></p>

                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row submit-btn-reg">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Đăng ký', 'woocommerce'); ?>"><?php esc_html_e('Đăng ký', 'woocommerce'); ?></button>
                    <a href="<?php echo home_url('/account') ?>" class="woocommerce-button button woocommerce-form-login" name="login"><?php esc_attr_e('Đăng nhập', 'woocommerce'); ?></a>
                </p>
                <?php do_action('woocommerce_register_form_end'); ?>

            </form>
        </div>
    </div>
<?php endif; ?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var textOnlyInput = document.getElementById('account_first_name');
        var textOnlyInputl = document.getElementById('account_last_name');

        textOnlyInput.addEventListener('input', function() {
            var inputValue = this.value;

            var textValue = inputValue.replace(/[0-9]/g, '');

            this.value = textValue;
        });
        textOnlyInputl.addEventListener('input', function() {
            var inputValue = this.value;
            var textValue = inputValue.replace(/[0-9]/g, '');
            this.value = textValue;
        });
    });
</script>
<style>
    .form-register-area {
        display: flex;
        justify-content: center;
		width: 450px;
    	margin: 0 auto;
    }

    .col-form-register {
        width: 100%;
        max-width: 600px;
        padding: 0px;
    }

    .title-register {
        text-align: center;
        font-size: 35px;
        font-weight: 600;
        color: #ff8264;
    }

    .woocommerce-form-register {
        padding: 0 !important;
        border: none !important;
    }

    .input-text {
        height: 42px !important;
        margin-top: 5px !important;
    }

    .submit-btn-reg {
        margin-top: 20px !important;
        text-align: center;
    }

    .woocommerce-form-register__submit {
        font-weight: 500 !important;
		text-transform: unset !important;
        padding: 15px 69px !important;
        margin-right: 10px !important;
        color: #fff !important;
        background-color: #ff8264 !important;
        border-radius: 25px !important;
    }

    .woocommerce-form-register__submit:hover {
        color: #9E0B0F !important;
        border-color: #9E0B0F !important;
        background-color: #ffff !important;
    }

    .woocommerce-form-login {
		text-transform: unset !important;
        padding: 15px 60px !important;
        font-weight: 500 !important;
        border-radius: 25px !important;
    }

    .woocommerce-privacy-policy-text {
        display: none !important;
    }

    @media (max-width: 480px) {
        .form-register-area {
            width: 375px !important;
            padding: 0px;
        }

        .title-register {
            font-size: 35px !important;
        }

        .woocommerce-form-register__submit {
            padding: 15px 51px !important;
        }

        .woocommerce-form-login {
            padding: 15px 40px !important;
        }
    }

    @media (max-width: 420px) {
        .form-register-area {
            width: 355px !important;
            padding: 0px;
        }
    }
</style>