<?php
 add_shortcode('lr-forgot-pwd-form', 'lr_forgot_pwd_form_callback');

  function lr_forgot_pwd_form_callback() {
      ob_start();
      if (!is_user_logged_in()) {
          global $getPasswordError, $getPasswordSuccess;

          if (!empty($getPasswordError)) {
              ?>
<div class="alert alert-danger">
    <?php echo $getPasswordError; ?>
</div>
<?php } ?>

<?php if (!empty($getPasswordSuccess)) { ?>
<br />
<div class="alert alert-success">
    <?php echo $getPasswordSuccess; ?>
</div>
<?php } ?>
<form method="post" class="wc-forgot-pwd-form">
    <div class="forgot_pwd_form">
        <div class="log_user">
            <label for="user_login">Username or E-mail:</label>
            <?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
            <input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" />
        </div>
        <div class="log_user">
            <?php
                      ob_start();
                      do_action('lostpassword_form');
                      echo ob_get_clean();
                      ?>
            <?php wp_nonce_field('userGetPassword', 'formType'); ?>
            <button type="submit" class="get_new_password">Get New Password</button>
        </div>
    </div>
</form>
<?php
      }

      $forgot_pwd_form = ob_get_clean();
      return $forgot_pwd_form;
  }

  add_action('wp', 'wc_user_forgot_pwd_callback');

  function wc_user_forgot_pwd_callback() {
      if (isset($_POST['formType']) && wp_verify_nonce($_POST['formType'], 'userGetPassword')) {
          global $getPasswordError, $getPasswordSuccess;

          $email = trim($_POST['user_login']);

          if (empty($email)) {
              $getPasswordError = '<strong>Error! </strong>Enter a e-mail address.';
          } else if (!is_email($email)) {
              $getPasswordError = '<strong>Error! </strong>Invalid e-mail address.';
          } else if (!email_exists($email)) {
              $getPasswordError = '<strong>Error! </strong>There is no user registered with that email address.';
          } else {

              // lets generate our new password
              $random_password = wp_generate_password(12, false);

              // Get user data by field and data, other field are ID, slug, slug and login
              $user = get_user_by('email', $email);

              $update_user = wp_update_user(array(
                  'ID' => $user->ID,
                  'user_pass' => $random_password
                      )
              );

              // if  update user return true then lets send user an email containing the new password
              if ($update_user) {
                  $to = $email;
                  $subject = 'Your new password';
                  $sender = get_bloginfo('name');

                  $message = 'Your new password is: ' . $random_password;

                  /* $headers[] = 'MIME-Version: 1.0' . "\r\n";
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers[] = "X-Mailer: PHP \r\n";
                    $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n"; */
                  $headers = array('Content-Type: text/html; charset=UTF-8');

                  $mail = wp_mail($to, $subject, $message, $headers);
                  if ($mail) {
                      $getPasswordSuccess = '<strong>Success! </strong>Check your email address for you new password.';
                  }
              } else {
                  $getPasswordError = '<strong>Error! </strong>Oops something went wrong.';
              }
          }
      }
  }
