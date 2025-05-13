<?php

    session_start();
    require_once '../../db/config.php';

    if(isset($_GET['category_id']))
    {
        $category_id = $_GET['category_id'];

        $viewSql = "SELECT * FROM categories WHERE category_id = :category_id";
        $stmtview = $conn->prepare($viewSql);
        $stmtview->bindParam(":category_id", $category_id);
        $stmtview->execute();
        $category = $stmtview->fetch();
    }


    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $category_name = $_POST['category_name'];

        if(empty($category_name))
        {
            $errorMessage = 'Το όνομα της κατηγορίας είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }

        $sql = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":category_name", htmlspecialchars($category_name));
        $stmt->bindParam(":category_id", htmlspecialchars($category_id));
        $result = $stmt->execute();

        if($result)
        {
            $successMessage = "Η κατηγορία δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/categories/update-category?category_id=$category_id");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/categories/update-category?category_id=$category_id");
            exit();
        }  

    }

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Update Category Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/categories/update-category.php?category_id=<?php echo $category_id; ?>" method="post">
            <label for="">Category Name</label>
            <input type="text" name="category_name" placeholder="write category name..." value="<?php echo $category['category_name'] ?>">
            <input type="submit" value="Update">
        </form>
</div>

<?php include_once '../../includes/footer.php'; ?>