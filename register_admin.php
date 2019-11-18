<?php
  session_start();
  require 'lib/password.php';
  require 'connectionPDO.php';
  function check_password($password) {
    if((strtolower($password) == $password) || (strtoupper($password) == $password) || (strlen($password) < 8 || strlen($password) >16)){
        echo("Password is less than 8 characters!");
        die;
    }
    if(!((strpos($password, '0') !== false) || (strpos($password, '1') !== false) || (strpos($password, '2') !== false) || (strpos($password, '3') !== false) || (strpos($password, '4') !== false) || (strpos($password, '5') !== false) || (strpos($password, '6') !== false) || (strpos($password, '7') !== false) || (strpos($password, '8') !== false) || (strpos($password, '9') !== false))){
        echo("The inserted email contains a space!");
        die;
    }
  }
  if(isset($_POST['register']) AND $_SERVER['REQUEST_METHOD'] === 'POST'){
      //Retrieve the field values from our registration form.
      $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
      $surname = !empty($_POST['surname']) ? trim($_POST['surname']) : null;
      $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
      $password2 = !empty($_POST['password2']) ? trim($_POST['password2']) : null;
      $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
      $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;

      // different password
      if($password !== $password2)
      {
          echo("You digited two different password!");
          return;
      }
      check_password($password);
      // forgot a value
      if(!isset($name) || !isset($surname) || !isset($password) || !isset($password2) || !isset($email) || !isset($phone))
      {
          echo("You forgot to enter at least one value!");
          return;
      }

      $sql = "SELECT COUNT(ID) AS num FROM Users WHERE Name = :name AND Surname = :surname AND Email = :email";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row['num'] > 0){
          die('That user already exists!');
      }
      $passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

      //Insert a new row into Users table
      $sql = "INSERT INTO Users (Name, Surname, Email, Phone, Password, Type, Validation) VALUES (:name, :surname, :email, :phone, :password, :type, :validation)";
      $type = "1";
      $validation = "1";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email, 'phone' => $phone, 'password' => $passwordHash, 'type' => $type, 'validation' => $validation]);
      if($result){
          //A message to user to advice that is register in the website
          echo 'Thank you for registering with our website.';
      }
  }

  ?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml" class="cufon-active cufon-ready" lang="en-US">
      <head profile="http://gmpg.org/xfn/11">
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="keywords" content="keyword1,keyword2">
          <meta name="description" content="Meta description here">
          <link rel="shortcut icon" href="linktoyourfavivon">
          <title>Register ADMIN</title>
      </head>
      <body>
          <h1>Register ADMIN</h1>
          <form action="register_user.php" method="post">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" required><br>

              <label for="surname">Surname</label>
              <input type="text" id="surname" name="surname" required><br>

              <label for="email">Email</label>
              <input type="email" id="email" name="email" required><br>

              <label for="phone">Phone</label>
              <input type="tel" id="phone" name="phone" required><br>

              <label for="password">Password</label>
              <input id="password" name="password" type="password" pattern="((?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,16})" placeholder="Password must have at least 8 characters and maximum of 15 characters with at least one Capital letter, at least one lower case letter and at least one number." required><br>

              <label for="password2">Repeat Password</label>
              <input id="password2" name="password2" type="password" pattern="((?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,16})" placeholder="Repeat the password" required><br>

              <input type="submit" name="register" value="Register">
          </form>
      </body>
</html>
