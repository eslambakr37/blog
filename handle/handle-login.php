<?php
require_once('../inc/connection.php');
if (isset($_POST['submit'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    //Vlidation

    //Email
    $errors = [];
    if (empty($email)) {
        $errors[] = 'email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'invalid email';
    }

    //Password
    if (empty($password)) {
        $errors[] = 'password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'invalid password';
    }


    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $runQuery = mysqli_query($conn, $query);
        if (mysqli_num_rows($runQuery) == 1) {
            $user = mysqli_fetch_assoc($runQuery);
            $userPassword = $user["password"];
            $isValid = password_verify($password, $userPassword);
            if ($isValid) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['success'] = "welcome back";
                header('location: ../index.php');
            } else {
                $_SESSION["errors"] = ["there are someting wrong in eamil or password"];
                $_SESSION["email"] = $email;
                header('location: ../login.php');
            }
        }
        else{
            $_SESSION['errors'] = ["there are someting wrong in eamil or password"];
            header('location: ../login.php');
        }
    } else {
        $_SESSION["errors"] = $errors;
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        header('location: ../login.php');
    }
} else {
    header('location: ../login.php');
}
