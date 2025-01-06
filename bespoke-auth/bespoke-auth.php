<?php 


class Bespoke_Auth {

  public function __construct() {

    $this->new_login = home_url( );
    $this->auth_dir = dirname(__FILE__);

    // redirect to custom login page
    add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );

    // shortcode for rendering custom form
    add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );

    // add a forgot password link
    add_filter('login_form_middle', array($this, 'add_forgot_password_link_to_form'));

    // handle authentication and hook in for error collection
    add_filter( 'authenticate', array( $this, 'check_authentication_for_errors' ), 101, 3 );
    
    // handle redirection on logout (and append query param)
    add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

    // handle redirection post-login
    add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );


  }

  //
  // REDIRECT FUNCTIONS
  //

  /**
   * Redirect the user to the custom login page instead of wp-login.php.
   */
  public function redirect_to_custom_login() {
    $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

      // logged in user gets sent to home
      if ( is_user_logged_in() ) {
        // TODO: check user role and redirect to appropriate dashboard
        wp_redirect( home_url( ) );
        exit;
      }

      // logged out user gets passed to home page (or any custom login page)
      $login_url = $this->new_login;

      // if there is a redirect param make sure to go ahead and attach it as well
      if ( ! empty( $redirect_to ) ) {
        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
      }

      wp_redirect( $login_url );
      exit;
    }
  }

  /**
   * Returns the URL to which the user should be redirected after the (successful) login.
   *
   * @param string           $redirect_to           The redirect destination URL.
   * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
   * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
   *
   * @return string Redirect URL
   */
  public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
    $redirect_url = home_url();

    if ( ! isset( $user->ID ) ) {
      return $redirect_url;
    }

    if ( user_can( $user, 'manage_options' ) ) {
      // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
      if ( $requested_redirect_to == '' ) {
        $redirect_url = home_url();
      } else {
        $redirect_url = $redirect_to;
      }
    } else {
      // Non-admin users always go to their dashboard login
      $redirect_url = home_url( );
    }

    return wp_validate_redirect( $redirect_url, home_url() );
  }

  /**
   * Redirect to custom login page after the user has been logged out.
   */
  public function redirect_after_logout() {
    $redirect_url = home_url( '?logged_out=true' );
    wp_safe_redirect( $redirect_url );
    exit;
  }

  /**
   * Redirect the user after authentication if there were any errors.
   *
   * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
   * @param string            $username   The user name used to log in.
   * @param string            $password   The password used to log in.
   *
   * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
   */
  public function check_authentication_for_errors( $user, $username, $password ) {
    // Check if the earlier authenticate filter (most likely,
    // the default WordPress authentication) functions have found errors
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
      if ( is_wp_error( $user ) ) {
        $error_codes = join( ',', $user->get_error_codes() );

        $login_url = home_url( );
        $login_url = add_query_arg( 'login', 'invalid', $login_url );
        // you can use the real error code, but it reveals if username exists
        //$login_url = add_query_arg( 'login', $error_codes, $login_url );

        wp_redirect( $login_url );
        exit;
      }
    }

    return $user;
  }


  //
  // FORM RENDERING SHORTCODES
  //

  /**
   * A shortcode for rendering the login form.
   *
   * @param  array   $attributes  Shortcode attributes.
   * @param  string  $content     The text content for shortcode. Not used.
   *
   * @return string  The shortcode output
   */
  public function render_login_form( $attributes, $content = null ) {
    // Parse shortcode attributes
    $default_attributes = array( 'show_title' => false );
    $attributes = shortcode_atts( $default_attributes, $attributes );

    if ( is_user_logged_in() ) {
      return "You are already signed in.";
    }

    // Pass the redirect parameter to the WordPress login functionality: by default,
    // don't specify a redirect, but if a valid redirect URL has been passed as
    // request parameter, use it.
    $attributes['redirect'] = '';
    if ( isset( $_REQUEST['redirect_to'] ) ) {
      $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
    }

    // Error messages
    $errors = array();
    if ( isset( $_REQUEST['login'] ) ) {
      $error_codes = explode( ',', $_REQUEST['login'] );

      foreach ( $error_codes as $code ) {
        $errors []= $this->get_error_message( $code );
      }
    }
    $attributes['errors'] = $errors;

    // Check if user just logged out
    $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

    // Render the login form using an external template
    return $this->get_template_html( 'login-form', $attributes );
  }


  function add_forgot_password_link_to_form($content) {
    return $content . '<a style="float:right;" class="forgot-password lost" href="' . wp_lostpassword_url() . '">Forgot password</a>';
  }


  //
  // HELPER FUNCTIONS
  //

   /**
   * Renders the contents of the given template to a string and returns it.
   *
   * @param string $template_name The name of the template to render (without .php)
   * @param array  $attributes    The PHP variables for the template
   *
   * @return string               The contents of the template.
   */
  private function get_template_html( $template_name, $attributes = null ) {
    if ( ! $attributes ) {
      $attributes = array();
    }

    ob_start();

    do_action( 'sbx_bespoke_login_before_' . $template_name );

    require(  $this->auth_dir . '/templates/' . $template_name . '.php');

    do_action( 'sbx_bespoke_login_after_' . $template_name );

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
  }


  /**
   * Finds and returns a matching error message for the given error code.
   *
   * @param string $error_code    The error code to look up.
   *
   * @return string               An error message.
   */
  private function get_error_message( $error_code ) {
    switch ( $error_code ) {
      // Login errors

      case 'empty_username':
        return 'You need to enter a username or email to login.';

      case 'empty_password':
        return 'You need to enter a password to login.';

      case 'invalid':
        return "There is an issue with the crendentials you provided. Please double check your username/email and password.";

      case 'invalid_username':
        return "There is an issue with the crendentials you provided. Please double check your username/email and password.";

      case 'incorrect_password':
        return "There is an issue with the crendentials you provided. Please double check your username/email and password.";
        // return sprintf( $err, wp_lostpassword_url() );

      default:
        break;
    }

    return 'An unknown error occurred. Please try again later.';
  }

}

// Initialize the class
$bespoke_auth_instance = new Bespoke_Auth();