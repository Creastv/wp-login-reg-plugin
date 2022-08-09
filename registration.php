<?php
  add_shortcode('lr-create-acount-form', 'lr_register_form_callback');

  function lr_register_form_callback() {
      ob_start();
      if (!is_user_logged_in()) {
          global $registrationError, $registrationSuccess;
          if (!empty($registrationError)) {
              ?>
              <div class="alert alert-danger">
                  <?php echo $registrationError; ?>
              </div>
          <?php } ?>

          <?php if (!empty($registrationSuccess)) { ?>
              <br/>
              <div class="alert alert-success">
                  <?php echo $registrationSuccess; ?>
              </div>
          <?php } ?>


          <form method="post" class="wc-register-form">
              <div class="register_form">
                  <div class="log_user">
                      <label for="user_name">User name</label>
                      <?php $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : ''; ?>
                      <input type="text" name="user_name" id="user_name" value="<?php echo $user_name; ?>" />
                  </div>

                  <div class="log_user">
                      <label for="user_email">Email address</label>
                      <?php $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : ''; ?>
                      <input type="email" name="user_email" id="user_email" value="<?php echo $user_email; ?>" />
                  </div>

                  <div class="log_pass">
                      <label for="user_password">Password</label>
                      <input type="password" name="user_password" id="user_password" />
                  </div>

                  <div class="log_pass">
                      <label for="user_cpassword">Password again</label>
                      <input type="password" name="user_cpassword" id="user_cpassword" />
                  </div>

                  <div class="log_pass">
                      <?php
                      ob_start();
                      do_action('register_form');
                      echo ob_get_clean();
                      ?>
                  </div>

                  <div class="log_user">
                      <?php wp_nonce_field('userRegister', 'formType'); ?>
                      <button type="submit" class="register_user">Register</button>
                  </div>
              </div>
          </form>
          <?php
      } else {
          echo '<p class="error-logged">You are already logged in.</p>';
      }
      $register_form = ob_get_clean();
      return $register_form;
  }

  add_action('wp', 'wc_user_register_callback');

  function wc_user_register_callback() {
      if (isset($_POST['formType']) && wp_verify_nonce($_POST['formType'], 'userRegister')) {

          global $registrationError, $registrationSuccess;

          $u_name = trim($_POST['user_name']);
          $u_email = trim($_POST['user_email']);
          $u_pwd = trim($_POST['user_password']);
          $u_cpwd = trim($_POST['user_cpassword']);

          if ($u_name == '') {
              $registrationError .= '<strong>Error! </strong> Enter User name.,';
          }

          if (username_exists($u_name)) {
              $registrationError .= '<strong>Error! </strong> Username In Use!.,';
          }

          if ($u_email == '') {
              $registrationError .= '<strong>Error! </strong> Enter Email.,';
          }

          if ($u_pwd == '' || $u_cpwd == '') {
              $registrationError .= '<strong>Error! </strong> Enter Password.,';
          }

          if (strlen($u_pwd) < 7) {
              $registrationError .= '<strong>Error! </strong> Use minimum 7 character in password.,';
          }

          if ($u_pwd != $u_cpwd) {
              $registrationError .= '<strong>Error! </strong> Password are not matching.,';
          }

          if ($u_email != '' && !is_email($u_email)) {
              $registrationError .= '<strong>Error! </strong> Invalid e-mail address.,';
          }

          if (email_exists($u_email) != false) {
              $registrationError .= '<strong>Error! </strong> This Email is already registered.,';
          }

          $registrationError = trim($registrationError, ',');
          $registrationError = str_replace(",", "<br/>", $registrationError);

          if (empty($registrationError)) {

              $user_login = $u_name;
              $user_email = $u_email;

              $userdata = array(
                  'user_login' => $user_login,
                  'user_pass' => $u_pwd,
                  'user_email' => $user_email
              );

              $errors = wp_insert_user($userdata);

              if (is_wp_error($errors)) {
                  $registrationError = $errors->get_error_message();
              } else {
                  $registrationSuccess = '<strong>Success! </strong> Application submitted. Please wait for user approval.';

                  wp_set_current_user($errors, $u_name);
                  wp_set_auth_cookie($errors);
                  do_action('wp_login', $u_name);

                  wp_redirect(site_url());
                  exit;
              }
          }
      }
  }