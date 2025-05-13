<?php

    session_start();

    require_once '../../db/config.php';

    $errorMessage = "";
    $successMessage = "";

    
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $tag_name = $_POST['tag_name'];

        if(empty($tag_name))
        {
            $errorMessage = 'Το όνομα της tag είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }

        $sql = "INSERT INTO tags(tag_name) VALUES (:tag_name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":tag_name", htmlspecialchars($tag_name));
        $result = $stmt->execute();

        if($result)
        {
            $successMessage = "To tag δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/tags/insert-tag");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/tags/insert-tag");
            exit();
        }  

    }

    $viewSql = "SELECT * FROM tags";
    $stmt = $conn->prepare($viewSql);
    $stmt->execute();
    $tags = $stmt->fetchAll();

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Insert Tags Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/tags/insert-tag.php" method="post">
            <label for="">Tag Name</label>
            <input type="text" name="tag_name" placeholder="write tag name..." value="<?php echo isset ($_POST['tag_name']) ? htmlspecialchars($_POST['tag_name']) : "" ;?>">
            <input type="submit" value="Insert">
        </form>
</div>
<div class="container-table">
    <table>
        <tr>
            <th> Tag ID </th>
            <th> Tag Name </th>
            <th> <img src="/blogspot/assets/updating.png" alt=""></th>
            <th> <img src="/blogspot/assets/delete.png" alt=""></th>
        </tr>
        <?php foreach($tags as $tag): ?>
            <tr>
                <td> <?php echo $tag['tag_id']; ?> </td>
                <td> <?php echo $tag['tag_name']; ?> </td>
                <td><a href="/blogspot/dashboard/tags/update-tag.php?tag_id=<?php echo $tag['tag_id']; ?>">Update</a></td>
                <td><a href="/blogspot/dashboard/tags/delete-tag.php?tag_id=<?php echo $tag['tag_id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>