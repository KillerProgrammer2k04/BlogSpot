<?php

    session_start();
    require_once '../db/config.php';

    if(!isset($_SESSION['user_id']))
    {
        header("Location: /blogspot/index");
        exit();
    }

    if(isset($_GET['user_id']))
    {
        $user_id = $_GET['user_id'];

        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        header("Location: /blogspot/dashboard/view-all-users.php");
        exit();
    }

?>