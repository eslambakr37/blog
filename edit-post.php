<?php require('inc/header.php'); ?>
<?php require('inc/navbar.php'); ?>
<?php
require_once('inc/connection.php');
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $_SESSION['id'] = $id;
    $query = "SELECT * FROM posts WHERE id = $id";
    $runQuery = mysqli_query($conn, $query);
    if (mysqli_num_rows($runQuery) == 1) {
        $post = mysqli_fetch_assoc($runQuery);
    } else {
        $_SESSION['error'] = 'No Data Founded';
        header("location: ../index.php");
    }
} else {
    header("location: index.php");
}
?>

<div class="container-fluid pt-4">
    <div class="w-50 m-auto">
        <?php
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) { ?>
                <p class='alert alert-danger'><?= $error; ?></p>
        <?php }
            unset($_SESSION['errors']);
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="d-flex justify-content-between border-bottom mb-5">
                <div>
                    <h3>Edit post</h3>
                </div>
                <div>
                    <a href="index.php" class="text-decoration-none">Back</a>
                </div>
            </div>
            <form method="POST" action="handle/update-post.php" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $post['title'] ?>">
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Body</label>
                    <textarea class="form-control" id="body" name="body" rows="5"><?= $post['body'] ?></textarea>
                </div>
                <div class="mb-3">
                    <?php if ($post['image'] != '') { ?>
                        <div class="my-2">
                            <img src="uploads/<?= $post['image'] ?>" alt="" srcset="" width="300px">
                        </div>
                    <?php } ?>
                    <label for="body" class="form-label">change image</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php require('inc/footer.php'); ?>