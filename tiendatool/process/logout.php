<?php 
  session_start();

  $_SESSION["user"]["loggedIn"] = false;
  $_SESSION["user"] = array();
  unset($_SESSION["user"]);
  header('Location: ../login-page.php?message=loggedOutSuccessfully');
  exit();