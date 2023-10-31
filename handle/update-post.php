<?php
require_once('../inc/connection.php');
if (isset($_POST['submit'])) {
    $id = $_SESSION['id'];
    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['body']));

    // Validation

    //title
    if (empty($title)) {
        $errors[] = "title is required";
    } elseif (is_numeric($title)) {
        $errors[] = "title most be string";
    } elseif (strlen($title) > 255) {
        $errors[] = "title must be less than or equal 255 char";
    }

    //body
    if (empty($body)) {
        $errors[] = "body is required";
    } elseif (is_numeric($body)) {
        $errors[] = "body most be string";
    }


    $query = "SELECT * FROM posts WHERE id = $id";
    $runQuery = mysqli_query($conn, $query);
    if (mysqli_num_rows($runQuery) == 1) {
        $post = mysqli_fetch_assoc($runQuery);
        $oldName = $post['image'];
    }

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
        $newName = $oldName;
    }

    if (empty($errors)) {
        //update
        if ($newName != $oldName) {
            move_uploaded_file($imageTmpName, "../uploads/$newName");
        }
        $query = "SELECT * FROM posts WHERE id = $id";
        $runQuery = mysqli_query($conn, $query);
        if (mysqli_num_rows($runQuery) == 1) {
            $query = "UPDATE posts SET `title`='$title' , `body`='$body' ,`image`='$newName' WHERE id=$id";
            $runQuery = mysqli_query($conn, $query);
            if ($runQuery) {
                $_SESSION['success'] = "post updated successfly";
                $query = "SELECT * FROM posts WHERE image = '$oldName'";
                $runQuery = mysqli_query($conn, $query);
                if (mysqli_num_rows($runQuery) == 0) {
                    unlink("../uploads/$oldName");
                }
                unset($_SESSION['id']);
                header("location: ../index.php");
            } else {
                $_SESSION['errors'] = "unexpected error while updating";
                header("location: ../edit-post.php?id=$id");
            }
        } else {
            $_SESSION['errors'] = 'no data found';
            unset($_SESSION['id']);
            header('location: ../index.php');
        }
    } else {
        $_SESSION['errors'] = $errors;
        header("location: ../edit-post.php?id=$id");
    }
} else {
    unset($_SESSION['id']);
    header('location: ../index.php');
}
