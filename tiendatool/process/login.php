<?php 
	session_start();
	require '../functions.php';

	$userEmail = esc($_POST['userEmail']);
	$userEmail = strtolower($userEmail);
	$userPassword = md5(esc($_POST['userPassword']));

//? Verify login

  // Ensure user came from the inde by submitting the login form
  if (!isset($_POST['login'])) {
    header("Location: ../index.php?message=loginFailed");
    exit();
  }
  
  // Check if user filled out the email and password
  if (empty($userEmail) || empty($userPassword)) {
    header("Location: ../index.php?message=missingValues");
    exit();
  }
  
  // Call userSelect function which returns $row if user exists, or false if user doesn't exist

  if ($userEmail != "micaela@cartelitos.app") {
    header("Location: ../index.php?message=userDoesNotExist");
    exit();
  }

  // Here you could check whether the user has verified their email by seeing whether $userDBData["token"] is still set to the randomly generated token from registration
  
  // Fail login if user provided values do not match with database values
  if ($userPassword != "bdfd58bd62d224df57797676c965e91b") {
    header("Location: ../index.php?message=incorrectPassword");
    exit();
  }
  // Log user in if user provided values match with database values
  if ($userPassword === "bdfd58bd62d224df57797676c965e91b") {
  
      $_SESSION["user"]["pass"] = $userPassword;
      $_SESSION["user"]["email"] = $userEmail;
      $_SESSION["user"]["loggedIn"] = true;
    header("Location: ../index.php?message=loginSuccess");
  }