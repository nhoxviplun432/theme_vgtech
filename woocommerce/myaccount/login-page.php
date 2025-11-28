<?php

/**
 * Login Form
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<?php
do_action('woocommerce_before_customer_login_form'); ?>
<div class="div-wrap-form-login form-login-area">
	<div class="col-2 col-form-login" id="customer_login">
		<h2 class="title-login"><?php esc_html_e('Đăng nhập', 'woocommerce'); ?></h2>
		<p style="display: none;">Do not have an account yet? <a style="color:#1d78d0;" href="<?php echo home_url(); ?>/account/?account_login=register">Sign Up</a></p>
		<form class="woocommerce-form woocommerce-form-login login" method="post" data-turbo="false">

			<?php do_action('woocommerce_login_form_start'); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e('Email', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																															?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide pw-row">
				<label for="password"><?php esc_html_e('Mật khẩu', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</p>
			<?php do_action('woocommerce_login_form'); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Ghi nhớ mật khẩu', 'woocommerce'); ?></span>
				</label>
				<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

			<div class="submit-losspw">
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Log In', 'woocommerce'); ?>"><?php esc_html_e('Đăng nhập', 'woocommerce'); ?></button>
				<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>?account_login=register" class="woocommerce-button button woocommerce-form-register" name="register"><?php esc_attr_e('Đăng ký', 'woocommerce'); ?></a>
			</div>
			<p class="forgot-password"><a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Quên mật khẩu?', 'woocommerce'); ?></a></p>
			<?php do_action('woocommerce_login_form_end'); ?>
			</p>
		</form>
	</div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
<style>
	.form-login-area {
		display: flex;
		justify-content: center;
		width: 450px;
		margin: 0 auto;
	}
							 
	.title-login {
		text-align: center;
        font-size: 35px;
        font-weight: 600;
        color: #ff8264;	
	}						  

	.col-form-login {
		width: 100%;
		max-width: 600px;
	}

	.col-form-login .woocommerce-form-login .woocommerce-form-login__submit {
		float: none !important;
	}

	.woocommerce-form-login {
		padding: 0px !important;
		border: none !important;
	}

	.woocommerce-Input {
		height: 42px;
		margin-top: 5px !important;
	}

	.woocommerce-form__label-for-checkbox {
		display: flex !important;
		align-items: center !important;
		margin-top: 15px !important;
	}

	.woocommerce-form__input-checkbox {
		width: 17px !important;
		height: 17px !important;
		margin-right: 10px !important;
	}

	.submit-losspw {
		text-align: center;
	}

	.woocommerce-form-login__submit {
		font-weight: 500 !important;
		text-transform: unset !important;
		padding: 15px 58px !important;
		color: #fff !important;
		background-color: #ff8264 !important;
		border-radius: 25px !important;
	}

	.woocommerce-form-login__submit:hover {
		color: #9E0B0F !important;
		border-color: #9E0B0F !important;
		background-color: #ffff !important;
	}

	.woocommerce-form-register {
		font-weight: 500 !important;
		text-transform: unset !important;
		padding: 15px 69px !important;
		border-radius: 25px !important;
	}

	.forgot-password {
		text-align: center !important;
		margin-top: 25px !important;
	}

	@media (max-width: 480px) {
		.title-login {
            font-size: 35px !important;
        }
							  
		.form-login-area {
			width: 375px;
		}

		.woocommerce-form-login__submit {
			padding: 15px 40px !important;
		}

		.woocommerce-form-register {
			padding: 15px 51px !important;
		}
	}

	@media (max-width: 420px) {
		.form-login-area {
			width: 355px;
		}

		.woocommerce-form-login__submit {
			padding: 15px 40px !important;
		}

		.woocommerce-form-register {
			padding: 15px 51px !important;
		}
	}
</style>