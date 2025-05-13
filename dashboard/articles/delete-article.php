<?php

    session_start();

    require_once '../../db/config.php';

    if(isset($_GET['id']))
    {

        $article_id = $_GET['id'];

        $sqlArtCat = "DELETE FROM articles_categories WHERE article_id = :article_id";
        $stmtArtCat = $conn->prepare($sqlArtCat);
        $stmtArtCat->bindParam(":article_id", $article_id);
        $stmtArtCat->execute();

        $sqlArtTag = "DELETE FROM articles_tags WHERE article_id = :article_id";
        $stmtArtTag = $conn->prepare($sqlArtTag);
        $stmtArtTag->bindParam(":article_id", $article_id);
        $stmtArtTag->execute();

        $sqlCom = "DELETE FROM comments WHERE article_id = :article_id";
        $stmtCom = $conn->prepare($sqlCom);
        $stmtCom->bindParam(":article_id", $article_id);
        $stmtCom->execute();

        $sqlArt = "DELETE FROM articles WHERE article_id = :article_id"; 
        $stmtArt = $conn->prepare($sqlArt);
        $stmtArt->bindParam(":article_id", $article_id);
        $stmtArt->execute();

        header("Location: /blogspot/dashboard/articles/view-articles.php");
        exit();
    }
?>