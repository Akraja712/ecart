<?php include('includes/crud.php');
$db = new Database();
$db->connect();
$sql = "SELECT value FROM `settings` where `variable` ='refund_policy'";
$db->sql($sql);
$res = $db->getResult();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width'>
    <title>Play store Refund Policy</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding: 1em;
        }
    </style>
</head>

<body>
    <?php echo $res[0]['value']; ?>
</body>

</html>