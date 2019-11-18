<?php
  date_default_timezone_set('UTC');
  session_start();
  if(!$_SESSION["auth"] OR ($_SESSION['type']!=0)) {
    echo('<meta http-equiv="refresh" content="0; ../login.php">');
    die;
  }
  $now = time();
  if($now > $_SESSION['expire']) {
      session_destroy();
      echo('<div class = "alert alert-danger">
                <strong> SESSION EXPIRED: </strong> Please, repeat login <a href="../login.php title="Login">here.</a>
            </div>');
      die;
  }
  $lifetime = 1200;
  $_SESSION['expire'] = $now + $lifetime;
  echo 'Congratulations! You are logged in!';
?>
