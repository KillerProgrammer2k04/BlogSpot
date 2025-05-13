<?php

    session_start();

    require_once '../../db/config.php';

    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $subcategory_id = $_POST['subcategory_id'];
        $tag_id = $_POST['tag_id'] ? $_POST['tag_id'] : [];
        $description = $_POST['description'];
        $article_image = $_FILES['article_image']['name'];
        $tmp_article_image = $_FILES['article_image']['tmp_name'];

        if(empty($title) && empty($description) && empty($article_image))
        {
            $errorMessage = 'όλα τα πεδία είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else
        {

            $folderarticle = "folderarticle/$title";

            if(!is_dir($folderarticle))
            {
                mkdir($folderarticle, 0777, true);
            }

            $filepath = "$folderarticle/$article_image";

            if(move_uploaded_file($tmp_article_image, $filepath))
            {
                $conn->beginTransaction();

                $sql = "INSERT INTO articles (title, description, article_image)
                        VALUES (:title, :description, :article_image)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':title', htmlspecialchars($title));            
                $stmt->bindParam(':description', htmlspecialchars($description));
                $stmt->bindParam(':article_image', $article_image);
                $stmt->execute();

                $article_id = $conn->lastInsertId();

                $sql = "INSERT INTO articles_categories (article_id, category_id, subcategory_id)
                VALUES (:article_id, :category_id, :subcategory_id)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':article_id', htmlspecialchars($article_id));            
                $stmt->bindParam(':category_id', htmlspecialchars($category_id));
                $stmt->bindParam(':subcategory_id', $subcategory_id);
                $stmt->execute();

                foreach ($tag_id as $tag_ids)
                {

                    $sql = "INSERT INTO articles_tags (article_id, tag_id)
                    VALUES (:article_id, :tag_id)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':article_id', htmlspecialchars($article_id));            
                    $stmt->bindParam(':tag_id', htmlspecialchars($tag_ids));
                    $stmt->execute();

                }   

                if($conn->commit())
                {
                    $successMessage = "Το άρθρο δημιουργήθηκε με επιτυχία!";
                    $_SESSION['SuccessMessage'] = $successMessage;
                    header("Location: /blogspot/dashboard/articles/insert-article");
                    exit(); 
                }
                else
                {
                    $errorMessage = "Κάτι πήγε στραβά!";
                    $_SESSION['ErrorMessage'] = $errorMessage;
                    header("Location: /blogspot/dashboard/articles/insert-article");
                    exit();
                }        
            }
        }
    }
    
        

    $viewcategories = "SELECT * FROM categories";
    $stmtcategories = $conn->prepare($viewcategories);
    $stmtcategories->execute();
    $categories = $stmtcategories->fetchAll();

    $viewsubcategories = "SELECT * FROM subcategories";
    $stmtsubcategories = $conn->prepare($viewsubcategories);
    $stmtsubcategories->execute();
    $subcategories = $stmtsubcategories->fetchAll();

    $viewtags = "SELECT * FROM tags";
    $stmttags = $conn->prepare($viewtags);
    $stmttags->execute();
    $tags = $stmttags->fetchAll();

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Insert Articles Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/articles/insert-article" method="post" enctype="multipart/form-data">
            <label for="">Title</label>
            <input type="text" name="title" placeholder="write title..." value="<?php echo isset ($_POST['title']) ? htmlspecialchars($_POST['title']) : "" ;?>">
            <label for="">Categories:</label>
            <select name="category_id" id="">
                <option value="" selected disabled>selected</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="">SubCategories:</label>
            <select name="subcategory_id" id="">
                <option value="" selected disabled>selected</option>
                <?php foreach ($subcategories as $subcategory) : ?>
                    <option value="<?php echo $subcategory['subcategory_id']; ?>"><?php echo $subcategory['subcategory_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="">Tags: </label>
            <?php foreach ($tags as $tag) : ?>
                <div>
                    <input type="checkbox" name="tag_id[]" value=<?php echo $tag['tag_id']; ?>>
                    <?php echo $tag['tag_name']; ?>
                </div>
            <?php endforeach; ?>
            <label for="">Description</label>
            <textarea name="description" placeholder="write description..." 
                value="<?php echo isset ($_POST['description']) ? htmlspecialchars($_POST['description']) : "" ;?>"></textarea>
            <label for="">Article Image</label>
            <input type="file" name="article_image">
            <input type="submit" value="Insert">
        </form>
</div>
<?php include_once '../../includes/footer.php'; ?>