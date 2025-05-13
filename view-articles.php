<?php

    session_start();

    require_once 'db/config.php';

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
<?php include_once 'includes/header.php';?>

<h1 class="page-title">View Articles Page</h1>


<div class="grid">
        <?php foreach($articles as $article): ?>
            <div class="col">
                <h2><?php echo $article['title']; ?></h2>
                <img src="/blogspot/dashboard/articles/folderarticle/<?php echo $article['title'];?>/<?php echo $article['article_image']; ?>">
                <p class="category"> category: <?php echo $article['category_name']; ?> </p>
                <p class="subcategory"> subcategory: <?php echo $article['subcategory_name']; ?> </p>
                <p class="date"> day time: <?php echo $article['create_at']; ?> </p>
                <a href="/blogspot/view-article.php?id=<?php echo $article['article_id']?>">View More</a>
             </div>
        <?php endforeach; ?>
</div>


<?php include_once 'includes/footer.php'; ?>