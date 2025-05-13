<?php

    session_start();
    require_once '../../db/config.php';

    if(!isset($_SESSION['user_id']))
    {
        header("Location: /blogspot/index");
        exit();
    }

    if(isset($_GET['tag_id']))
    {
        $tag_id = $_GET['tag_id'];

        $sql = "DELETE FROM tags WHERE tag_id = :tag_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":tag_id", $tag_id);
        $stmt->execute();
        header("Location: /blogspot/dashboard/tags/insert-tag");
        exit();
    }

?>
