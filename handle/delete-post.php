<?php
require_once("../inc/connection.php");
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $query = "SELECT * FROM posts WHERE id = $id";
    $runQuery = mysqli_query($conn, $query);
    if (mysqli_num_rows($runQuery) == 1) {
        $post = mysqli_fetch_assoc($runQuery);
        if ($post["image"] != "") {
            $imagePath = $post["image"];
        }
        $query = "DELETE FROM posts WHERE id = $id";
        $runQuery = mysqli_query($conn, $query);
        if ($runQuery) {
            $_SESSION['success'] = 'Data deleted Successfully';
            $query = "SELECT * FROM posts WHERE image = '$imagePath'";
            $runQuery = mysqli_query($conn, $query);
            if (mysqli_num_rows($runQuery) == 0) {
                unlink("../uploads/$imagePath");
            }
            header("location: ../index.php");
        } else {
            $_SESSION['error'] = 'unexpected error while deleting';
            header("location: ../index.php");
        }
    } else {
        $_SESSION['error'] = 'No Data Founded';
        header("location: ../index.php");
    }
} else {
    header("location: ../index.php");
}
