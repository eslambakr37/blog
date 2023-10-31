<?php 
require_once('../inc/connection.php');
// unset($_SESSION['user_id']);
session_destroy();
header('location: ../index.php');


