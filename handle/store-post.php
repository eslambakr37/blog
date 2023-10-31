<?php
require_once("../inc/connection.php");
if (isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $body = htmlspecialchars(trim($_POST["body"]));
    // htmlspecialchars to avoid if the user tries to input a JS code witch will force the browser to consider this code as Char && trim to remove spaces before text

    $errors = [];
    // Validation

    //title
    if (empty($title)) :
        $errors[] = "title is required";
    elseif (is_numeric($title)) :
        $errors[] = "title most be string";
    elseif (strlen($title) > 255) :
        $errors[] = "title must be less than or equal 255 char";
    endif;

    //body
    if (empty($body)) :
        $errors[] = "body is required";
    elseif (is_numeric($body)) :
        $errors[] = "body most be string";
    endif;

    //image
    if ($_FILES && $_FILES['image']['name']) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'] / (1024 * 1024); //Size in MB
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        $newName = uniqid() . "." . $imageExt;
        if (!in_array(strtolower($imageExt), ["png", "jpg", "jpeg", "gif"])) {
            $errors[] = "image extention not allowed";
        } elseif ($imageSize > 1) {
            $errors[] = "image size more than 1Mb";
        }
    } else {
        $newName = '';
    }

    if (empty($errors)) {
        //insert
        $query = "INSERT INTO `posts`(`title`,`body`,`image`,`user_id`) VALUES('$title','$body','$newName',1)";
        $runQuery = mysqli_query($conn, $query);
        if ($runQuery) {
            if ($newName != '') {
                move_uploaded_file($imageTmpName, "../uploads/$newName");
            }
            $_SESSION['success'] = "post uploaded successfly";
            header("location: ../index.php");
        } else {
            $_SESSION['errors'] = "unexpected error while uploading";
            header("location: ../create-post.php");
        }
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['title'] = $title;
        $_SESSION['body'] = $body;
        header("location: ../create-post.php");
    }
} else {
    header("location: ../index.php");
}
