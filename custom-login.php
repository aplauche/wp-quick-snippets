<?php  

namespace MY_THEME\inc\customLogin;


function login_styles() {
  ?>
  <style>
      /* Custom logo */
      .login h1 a {
          background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/login-logo-ipm.png') !important;
          background-size: contain;
          width: 100%;
          height: 84px;
          display: block;
      }

      /* Custom background color */
      body.login {
          --primary-color: rgb(163, 101, 56);
          --secondary-color: rgb(205, 150, 111);
          background-color: white; /* Replace with your desired color */
      }

      /* Custom form styles */
      body.login  form {
          background-color: #ffffff; /* Replace with your desired color */
          border: 1px solid #ccc;
          border-radius: 8px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }

      body.login  label {
          color: #333;
      }

      body.login  form .input, 
      body.login  input[type="text"], 
      body.login  input[type="password"], 
      body.login  input[type="email"] {
          background-color: #fdfdfd;
          border: 1px solid #ddd;
          border-radius: 4px;
      }

      body.login  .button-primary {
          background-color: var(--secondary-color); 
          border-color: var(--primary-color);
          box-shadow: 0 1px 0 var(--primary-color);
      }

      body.login  .button-primary:hover {
          background-color: var(--primary-color);
          border-color: var(--primary-color);
      }

      /* Custom footer message */
      body.login  #backtoblog a, 
      body.login  #nav a {
          color: var(--secondary-color); 
      }
      body.login  #backtoblog a:hover, 
      body.login  #nav a:hover {
          color: var(--primary-color); /* Customize hover color */
      }
  </style>
  <?php
}
add_action('login_enqueue_scripts', __NAMESPACE__ . '\login_styles', 999);

function login_url() {
  return home_url(); // Change the logo link to your site home URL
}
add_filter('login_headerurl', __NAMESPACE__ . '\login_url');

function login_title() {
  return get_bloginfo('name'); // Change the logo title to your site name
}
add_filter('login_headertext', __NAMESPACE__ . '\login_title');
