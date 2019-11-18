<?php
  //Change user, password, host and name of db with your parameters

  //MySQL user account.
  define('MYSQL_USER', 'MySql_username');

  //MySQL password.
  define('MYSQL_PASSWORD', 'MySql_password');

  //Server that MySQL is located on.
  //Default host name is localhost.
  define('MYSQL_HOST', 'MySql_host');

  //Name of database.
  define('MYSQL_DATABASE', 'MySql_name-of-database');

  $pdoOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false
  );

  /**
  * Connect to MySQL and instantiate the PDO object.
  */
  $pdo = new PDO(
    "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE, //DSN
    MYSQL_USER, //Username
    MYSQL_PASSWORD, //Password
    $pdoOptions //Options
  );
?>
