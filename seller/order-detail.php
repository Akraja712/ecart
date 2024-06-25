<?php
// start session
session_start();
// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;
// if session not set go to login page
if (!isset($_SESSION['seller_id']) && !isset($_SESSION['seller_name'])) {
    header("location:index.php");
}
// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php include "header.php"; ?>

<html>

<head>
    <title>Order Details | <?= $settings['app_name'] ?> - Dashboard</title>
    <style>

    </style>
</head>

</body>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?php include('public/orders-data.php'); ?>
</div>
<!-- /.content-wrapper -->
</body>

</html>
<?php include "footer.php"; ?>
