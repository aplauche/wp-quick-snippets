<div class="form-container">
	<!-- Show errors if there are any -->
	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
		<?php foreach ( $attributes['errors'] as $error ) : ?>
			<p class="login-error">
				<?php echo $error; ?>
			</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<!-- Show logged out message if user just logged out -->
	<?php if ( $attributes['logged_out'] ) : ?>
		<p class="login-info">
			You have been signed out.
		</p>
	<?php endif; ?>

	<?php
		wp_login_form(
			array(
				'label_username' => 'Email / Username',
				'label_log_in' => 'Sign In',
				'redirect' => $attributes['redirect'],
        'label_remember' => 'Remember me'
			)
		);
	?>

  <?php 
  
    /*

    ALTERNATIVE CUSTOM FORM APPROACH

    <div class="login-form-container">
    <form method="post" action="<?php echo wp_login_url(); ?>">
        <p class="login-username">
            <label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
            <input type="text" name="log" id="user_login">
        </p>
        <p class="login-password">
            <label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
            <input type="password" name="pwd" id="user_pass">
        </p>
        <p class="login-submit">
            <input type="submit" value="<?php _e( 'Sign In', 'personalize-login' ); ?>">
        </p>
    </form>
    </div>
    */
  
  ?>



</div>