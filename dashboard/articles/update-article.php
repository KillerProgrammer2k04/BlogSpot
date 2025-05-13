<?php

    session_start();

    require_once '../../db/config.php';

    $errorMessage = "";
    $successMessage = "";

    if(isset($_GET['id']))
    {

        $article_id = $_GET['id'];

        $sqlSelArt = "SELECT * FROM articles WHERE article_id = :article_id";
        $stmtSelArt = $conn->prepare($sqlSelArt);
        $stmtSelArt->bindParam(":article_id", $article_id);
        $stmtSelArt->execute();
        $article = $stmtSelArt->fetch();

        // Get selected category and subcategory for article
        $sqlCatSubcat = "SELECT category_id, subcategory_id FROM articles_categories WHERE article_id = :article_id";
        $stmtCatSubcat = $conn->prepare($sqlCatSubcat);
        $stmtCatSubcat->bindParam(":article_id", $article_id);
        $stmtCatSubcat->execute();
        $articleCats = $stmtCatSubcat->fetch();
        if ($articleCats) {
            $article['category_id'] = $articleCats['category_id'];
            $article['subcategory_id'] = $articleCats['subcategory_id'];
        }

        // Get selected tags
        $sqlArticleTags = "SELECT tag_id FROM articles_tags WHERE article_id = :article_id";
        $stmtArticleTags = $conn->prepare($sqlArticleTags);
        $stmtArticleTags->bindParam(":article_id", $article_id);
        $stmtArticleTags->execute();
        $selectedTags = $stmtArticleTags->fetchAll(PDO::FETCH_COLUMN); // πίνακας tag_id

    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $subcategory_id = $_POST['subcategory_id'];
        $tag_id = isset($_POST['tag_id']) ? $_POST['tag_id'] : [];
        $description = $_POST['description'];
        $article_image = $_FILES['article_image']['name'];
        $tmp_article_image = $_FILES['article_image']['tmp_name'];
    
        if (empty($title) || empty($description)) {
            $errorMessage = 'Όλα τα πεδία είναι υποχρεωτικά!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        } else {
            // Δημιουργία φακέλου για το άρθρο
            $folder = "folderarticle";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
    
            $safeTitle = preg_replace('/[^\pL\d]+/u', '-', $title); // καθαρό όνομα φακέλου
            $folderarticle = "$folder/$safeTitle";
            if (!is_dir($folderarticle)) {
                mkdir($folderarticle, 0777, true);
            }
    
            // Χειρισμός εικόνας
            if (!empty($article_image)) {
                $filepath = "$folderarticle/$article_image";
                move_uploaded_file($tmp_article_image, $filepath);
            } else {
                $article_image = $article['article_image']; // κράτησε την υπάρχουσα εικόνα
            }
    
            // Έναρξη συναλλαγής
            $conn->beginTransaction();
    
            // Ενημέρωση άρθρου
            $sql = "UPDATE articles 
                    SET title = :title, description = :description, article_image = :article_image 
                    WHERE article_id = :article_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', htmlspecialchars($title));
            $stmt->bindParam(':description', htmlspecialchars($description));
            $stmt->bindParam(':article_image', $article_image);
            $stmt->bindParam(':article_id', $article_id);
            $stmt->execute();
    
            // Ενημέρωση κατηγορίας/υποκατηγορίας
            $sql = "UPDATE articles_categories 
                    SET category_id = :category_id, subcategory_id = :subcategory_id 
                    WHERE article_id = :article_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':subcategory_id', $subcategory_id);
            $stmt->bindParam(':article_id', $article_id);
            $stmt->execute();
    
            // Διαγραφή παλιών tags
            $sql = "DELETE FROM articles_tags WHERE article_id = :article_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':article_id', $article_id);
            $stmt->execute();
    
            // Εισαγωγή νέων tags
            foreach ($tag_id as $tag_ids) {
                $sql = "INSERT INTO articles_tags (article_id, tag_id) VALUES (:article_id, :tag_id)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':article_id', $article_id);
                $stmt->bindParam(':tag_id', $tag_ids);
                $stmt->execute();
            }
    
            // Επιτυχία ή rollback
            if ($conn->commit()) {
                $_SESSION['SuccessMessage'] = "Το άρθρο ενημερώθηκε με επιτυχία!";
                header("Location: /blogspot/dashboard/articles/update-article?id=$article_id");
                exit();
            } else {
                $_SESSION['ErrorMessage'] = "Κάτι πήγε στραβά!";
                header("Location: /blogspot/dashboard/articles/update-article?id=$article_id");
                exit();
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

<h1 class="page-title">Update Articles Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/articles/update-article?id=<?php echo $article_id; ?>" method="post" enctype="multipart/form-data">
            <label for="">Title</label>
            <input type="text" name="title" placeholder="write title..." value="<?php echo $article['title']; ?>">
            <label for="">Categories:</label>
            <select name="category_id" id="">
            <option value="" disabled>selected</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['category_id']; ?>"
                        <?php echo (isset($article['category_id']) && $article['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                        <?php echo $category['category_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="">SubCategories:</label>
            <select name="subcategory_id" id="">
            <option value="" disabled>selected</option>
                <?php foreach ($subcategories as $subcategory) : ?>
                    <option value="<?php echo $subcategory['subcategory_id']; ?>"
                        <?php echo (isset($article['subcategory_id']) && $article['subcategory_id'] == $subcategory['subcategory_id']) ? 'selected' : ''; ?>>
                        <?php echo $subcategory['subcategory_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="">Tags: </label>
            <?php foreach ($tags as $tag) : ?>
            <div>
                <input type="checkbox" name="tag_id[]" value="<?php echo $tag['tag_id']; ?>"
                    <?php echo in_array($tag['tag_id'], $selectedTags ?? []) ? 'checked' : ''; ?>>
                <?php echo $tag['tag_name']; ?>
            </div>
            <?php endforeach; ?>
            <label for="">Description</label>
            <textarea name="description" placeholder="write description..." ><?php echo $article['description']; ?></textarea>
            <label for="">Article Image</label>
            <input type="file" name="article_image">
            <img src="/blogspot/dashboard/articles/folderarticle/<?php echo $article['title']; ?>/<?php echo $article['article_image']; ?>" alt="" style="width: 150px;">
            <input type="submit" value="Update">
        </form>
</div>
<?php include_once '../../includes/footer.php'; ?>