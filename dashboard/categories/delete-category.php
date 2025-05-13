<?php

    session_start();
    require_once '../../db/config.php';

    if(!isset($_SESSION['user_id']))
    {
        header("Location: /blogspot/index");
        exit();
    }

    if(isset($_GET['category_id']))
    {
        $category_id = $_GET['category_id'];

        $sql = "DELETE FROM categories WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        header("Location: /blogspot/dashboard/categories/insert-category");
        exit();
    }

?>
