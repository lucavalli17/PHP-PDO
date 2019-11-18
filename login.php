<?php
  require 'lib/password.php';
  require 'connectionPDO.php';
  if(isset($_POST['login'])){
      $lifetime = 1200;
      session_start();
      $_SESSION['auth'] = false;

      //Retrieve the field values from our login form.
      $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
      echo "email --> ".$email;
      $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

      $sql = "SELECT ID, Email, Password, Type, Validation FROM Users WHERE Email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email', $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user === false){
          //Could not find a user with that username!
          //PS: You might want to handle this error in a more user-friendly manner!
          die('Incorrect username / password combination!');
      }
      else if($user['Validation'] == 0){
          die("Your account doesn't exists!");
          //When the account exists but it's not validated
      }
      else{
          $validPassword = password_verify($passwordAttempt, $user['Password']);
          if($validPassword){
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['type'] = $user['Type'];
            $_SESSION['auth'] = true;

            //USER
            if($_SESSION['type'] == '0'){
              $_SESSION['expire'] = time() + $lifetime;
              header('Location: ../pdo/user_area/index.php');
            }

            //ADMIN
            else if($_SESSION['type'] == '1'){
              $_SESSION['expire'] = time() + $lifetime;
              header('Location: ../pdo/admin_area/index.php');
            }

            exit;

          } else{
              //$validPassword was FALSE. Passwords do not match.
              die('Incorrect username / password combination!');
          }
      }

  }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"><br>

            <input type="submit" name="login" value="Login">
        </form>
    </body>
</html>
