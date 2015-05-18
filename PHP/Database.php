<?php
    require_once("Protocol.php");

    $DSN = "mysql:host=$DatabaseIP;dbname=$DatabaseName";
    $Database = new PDO($DSN,$DatabaseAccount,$DatabasePassword);
    if(!isset($Database))
    {
        exit();
    }
    $Database->query("set character_set_results='utf8'");
    $Database->query("set character_set_client='utf8'");
    $Database->query("set collation_connection='utf8_general_ci'");
?>
