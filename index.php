<?php
    session_start();
    require_once("PHP/Database.php");
    require_once("PHP/Page.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>DB_Project1</title>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="Script/Control.js"></script>
        <script src="Script/Select.js"></script>
        <script src="Script/GetPage.js"></script>
        <script src="Script/Action.js"></script>
        <link type="text/css" href="CSS/StyleSheet.css" rel="stylesheet">
        <link type="text/css" href="CSS/Button.css" rel="stylesheet">
    </head>
    <body>
        <?php
            echo IndexPage($Database);
        ?>
    </body>
</html>
