<?php

    session_start();

    require_once '../../db/config.php';

    $viewartices = 
    "SELECT
    articles.article_id,
    articles.title,
    articles.article_image,
    articles.create_at,
    categories.category_name,
    subcategories.subcategory_name
    FROM articles
    LEFT JOIN articles_categories ON articles.article_id = articles_categories.article_id
    LEFT JOIN categories ON articles_categories.category_id = categories.category_id
    LEFT JOIN subcategories ON articles_categories.subcategory_id = subcategories.subcategory_id
    ";
    $stmtarticles = $conn->prepare($viewartices);
    $stmtarticles->execute();
    $articles = $stmtarticles->fetchAll();

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">View Articles Page</h1>
<div class="container-table">
    <table>
        <tr>
            <th> Article ID </th>
            <th> Title </th>
            <th> Category Name </th>
            <th> Subcategory Name</th>
            <th> Create at </th>
            <th> <img src="/blogspot/assets/updating.png" alt=""></th>
            <th> <img src="/blogspot/assets/delete.png" alt=""></th>
        </tr>
        <?php foreach($articles as $article): ?>
        <tr>
            <td> <?php echo $article['article_id']; ?> </td>
            <td> <?php echo $article['title']; ?> </td>
            <td> <?php echo $article['category_name']; ?> </td>
            <td> <?php echo $article['subcategory_name']; ?></td>
            <td> <?php echo $article['create_at']; ?> </td>
            <td>  <a href="/blogspot/dashboard/articles/update-article.php?id=<?php echo $article['article_id']?>">Update</a></td>
            <td>  <a href="/blogspot/dashboard/articles/delete-article.php?id=<?php echo $article['article_id']?>">Delete</a></td>
        </tr> 
        <?php endforeach; ?>
    </table>
</div>

      



<?php include_once '../../includes/footer.php'; ?>