<?php require('inc/header.php'); ?>
<?php require('inc/navbar.php'); ?>

<div class="container-fluid pt-4">
    <div class="w-50 m-auto">
        <?php
        require_once('inc/connection.php');
        if (isset($_SESSION['success'])) {
        ?>
            <p class='alert alert-success'><?= $_SESSION['success']; ?></p>
        <?php
            unset($_SESSION['success']);
        }
        ?>
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
                    <h3>Login</h3>
                </div>
                <div>
                    <a href="index.php" class="text-decoration-none">Back</a>
                </div>
            </div>
            <form method="POST" action="handle/handle-login.php">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value='<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; unset($_SESSION['email']); ?>'>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value='<?php if(isset($_SESSION['password'])) echo $_SESSION['password']; unset($_SESSION['password']); ?>'>
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Login</button>
            </form>
        </div>
    </div>
</div>

<?php require('inc/footer.php'); ?>