<?php

    session_start();

    require_once '../../db/config.php';

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

        $sql = "INSERT INTO categories(category_name) VALUES (:category_name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":category_name", htmlspecialchars($category_name));
        $result = $stmt->execute();

        if($result)
        {
            $successMessage = "Η κατηγορία δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/categories/insert-category");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/categories/insert-category");
            exit();
        }  

    }

    $viewSql = "SELECT * FROM categories";
    $stmt = $conn->prepare($viewSql);
    $stmt->execute();
    $categories = $stmt->fetchAll();

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Insert Category Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/categories/insert-category.php" method="post">
            <label for="">Category Name</label>
            <input type="text" name="category_name" placeholder="write category name..." value="<?php echo isset ($_POST['category_name']) ? htmlspecialchars($_POST['category_name']) : "" ;?>">
            <input type="submit" value="Insert">
        </form>
</div>
<div class="container-table">
    <table>
        <tr>
            <th> Category ID </th>
            <th> Category Name </th>
            <th> <img src="/blogspot/assets/updating.png" alt=""></th>
            <th> <img src="/blogspot/assets/delete.png" alt=""></th>
        </tr>
        <?php foreach($categories as $category): ?>
            <tr>
                <td> <?php echo $category['category_id']; ?> </td>
                <td> <?php echo $category['category_name']; ?> </td>
                <td><a href="/blogspot/dashboard/categories/update-category.php?category_id=<?php echo $category['category_id']; ?>">Update</a></td>
                <td><a href="/blogspot/dashboard/categories/delete-category.php?category_id=<?php echo $category['category_id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php include_once '../../includes/footer.php'; ?>