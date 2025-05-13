<?php

    session_start();

    require_once '../../db/config.php';

    if(isset($_GET['subcategory_id']))
    {
        $subcategory_id = $_GET['subcategory_id'];

        $viewSql = "SELECT * FROM subcategories WHERE subcategory_id = :subcategory_id";
        $stmtview = $conn->prepare($viewSql);
        $stmtview->bindParam(":subcategory_id", $subcategory_id);
        $stmtview->execute();
        $subcategory = $stmtview->fetch();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $category_id = $_POST['category_id'];
        $subcategory_name = $_POST['subcategory_name'];

       
        $sql = "UPDATE subcategories SET category_id = :category_id, subcategory_name = :subcategory_name WHERE subcategory_id = :subcategory_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":category_id", htmlspecialchars($category_id));
        $stmt->bindParam(":subcategory_name", htmlspecialchars($subcategory_name));
        $stmt->bindParam(":subcategory_id", htmlspecialchars($subcategory_id));

        $result = $stmt->execute();

        if($result)
        {
            $successMessage = "Η υποκατηγορία δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/subcategories/update-subcategory?subcategory_id=$subcategory_id");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/subcategories/update-subcategory?subcategory_id=$subcategory_id");
            exit();
        }  
        
    }

    $viewSql = "SELECT * FROM categories";
    $stmtview = $conn->prepare($viewSql);
    $stmtview->execute();
    $categories = $stmtview->fetchAll();

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Update Subcategory Page</h1>

<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/subcategories/update-subcategory.php?subcategory_id=<?php echo $subcategory_id; ?>" method="post">
            <label for="">Category Name</label>
            <select name="category_id" id="">
                <?php foreach($categories as $category):?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="">Subcategory Name</label>
            <input type="text" name="subcategory_name" placeholder="write subcategory name..." value="<?php echo $subcategory['subcategory_name'];?>">
            <input type="submit" value="Update">
        </form>
</div>
<?php include_once '../../includes/footer.php'; ?>