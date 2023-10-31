<?php require('inc/header.php'); ?>
<?php require('inc/navbar.php'); ?>
<?php require_once('inc/connection.php');
$limit = 3;
isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
$query = "SELECT COUNT(id) AS total FROM `posts`";
$runQuery = mysqli_query($conn, $query);
if (mysqli_num_rows($runQuery) > 0) {
    $totalPosts = mysqli_fetch_assoc($runQuery);
    $numOfPages = ceil($totalPosts["total"] / $limit);
    if ($page > $numOfPages) {
        $page = $numOfPages;
    } elseif ($page < 1) {
        $page = 1;
        // header("location:".$_SERVER['PHP_SELF']."?page=$page");
    }
    $offset = ($page - 1) * $limit;
    $query = "SELECT * FROM `posts`  LIMIT $limit OFFSET $offset";
    $runQuery = mysqli_query($conn, $query);
    $posts = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);
} else {
    $message = "No posts Founded";
}
?>
<div class="container-fluid pt-4">
    <div class="w-50 m-auto">
        <?php
        if (isset($_SESSION['success'])) {
        ?>
            <p class='alert alert-success'><?= $_SESSION['success']; ?></p>
        <?php
            unset($_SESSION['success']);
        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="d-flex justify-content-between border-bottom mb-5">
                <div>
                    <h3>All posts</h3>
                </div>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div>
                        <a href="create-post.php" class="btn btn-sm btn-success">Add new post</a>
                    </div>
                <?php } ?>
            </div>
            <?php if (!empty($posts)) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Published At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post) : ?>
                            <tr>
                                <td><?= $post['title'] ?></td>
                                <td><?= $post['created_at'] ?></td>
                                <td>
                                    <a href="show-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">Show</a>
                                    <?php if (isset($_SESSION['user_id'])) { ?>
                                        <a href="edit-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="handle/delete-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('do you really want to delete post?')">Delete</a>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : echo $message;
            endif; ?>
        </div>
    </div>

    <ul class="pagination d-flex justify-content-center">
        <li class="page-item">
            <a class="page-link" href=<?= $_SERVER['PHP_SELF'] . "?page=" . ($page == 1 ? 1 : $page - 1) ?> aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for ($i = 1; $i <= $numOfPages; $i++) : ?>
            <li class="page-item"><a class="page-link" href=<?= $_SERVER['PHP_SELF'] . "?page=$i" ?>><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item">
            <a class="page-link" href=<?= $_SERVER['PHP_SELF'] . "?page=" . ($page == $numOfPages ? $numOfPages : $page + 1) ?> aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
            <?= $page ?>
        </li>
    </ul>
</div>

<?php require('inc/footer.php'); ?>