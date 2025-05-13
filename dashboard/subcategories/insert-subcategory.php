<?php

    session_start();

    require_once '../../db/config.php';

    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $category_id = $_POST['category_id'];
        $subcategory_name = $_POST['subcategory_name'];

        if(empty($subcategory_name))
        {
            $errorMessage = 'Το όνομα της υποκατηγορίας είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }

        $sql = "INSERT INTO subcategories(subcategory_name, category_id) VALUES (:subcategory_name, :category_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":subcategory_name", htmlspecialchars($subcategory_name));
        $stmt->bindParam(":category_id", htmlspecialchars($category_id));

        $result = $stmt->execute();

        if($result)
        {
            $successMessage = "Η υποκατηγορία δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/subcategories/insert-subcategory");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/subcategories/insert-subcategory");
            exit();
        }  
    }

$viewcategories = "SELECT * FROM categories";
$stmtcategories = $conn->prepare($viewcategories);
$stmtcategories->execute();
$categories = $stmtcategories->fetchAll();

$viewsubcategories = 
"SELECT subcategories.subcategory_id, subcategories.subcategory_name, categories.category_name
FROM subcategories
LEFT JOIN categories ON subcategories.category_id = categories.category_id
";
$stmtsubcategories = $conn->prepare($viewsubcategories);
$stmtsubcategories->execute();
$subcategories = $stmtsubcategories->fetchAll();


?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Insert Subsubcategory Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/subcategories/insert-subcategory.php" method="post">
            <label for="">Category Name</label>
            <select name="category_id" id="">
                <?php foreach($categories as $category):?>
                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="">Subcategory Name</label>
            <input type="text" name="subcategory_name" placeholder="write subcategory name..." value="<?php echo isset ($_POST['subcategory_name']) ? htmlspecialchars($_POST['subcategory_name']) : "" ;?>">
            <input type="submit" value="Insert">
        </form>
</div>
<div class="container-table">
    <table>
        <tr>
            <th> Subcategory ID </th>
            <th> Category Name </th>
            <th> Subcategory Name </th>
            <th> <img src="/blogspot/assets/updating.png" alt=""></th>
            <th> <img src="/blogspot/assets/delete.png" alt=""></th>
        </tr>
        <?php foreach($subcategories as $subcategory): ?>
            <tr>
                <td> <?php echo $subcategory['subcategory_id']; ?> </td>
                <td> <?php echo $subcategory['category_name']; ?> </td>
                <td> <?php echo $subcategory['subcategory_name']; ?> </td>
                <td><a href="/blogspot/dashboard/subcategories/update-subcategory.php?subcategory_id=<?php echo $subcategory['subcategory_id']; ?>">Update</a></td>
                <td><a href="/blogspot/dashboard/subcategories/delete-subcategory.php?subcategory_id=<?php echo $subcategory['subcategory_id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>