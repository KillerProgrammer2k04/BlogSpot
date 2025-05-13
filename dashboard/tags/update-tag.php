<?php

    session_start();
    require_once '../../db/config.php';

    if(isset($_GET['tag_id']))
    {
        $tag_id = $_GET['tag_id'];

        $sql = "SELECT * FROM tags WHERE tag_id = :tag_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":tag_id", $tag_id);
        $stmt->execute();
        $tag = $stmt->fetch();
    }

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

        $sqlUpdate = "UPDATE tags SET tag_name =  :tag_name WHERE tag_id = :tag_id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(":tag_name", htmlspecialchars($tag_name));
        $stmtUpdate->bindParam(":tag_id", htmlspecialchars($tag_id));
        $result = $stmtUpdate->execute();

        if($result)
        {
            $successMessage = "To tag δημιουργήθηκε με επιτυχία!";
            $_SESSION['SuccessMessage'] = $successMessage;
            header("Location: /blogspot/dashboard/tags/update-tag?tag_id=$tag_id");
            exit();
        }
        else
        {
            $errorMessage = "Κάτι πήγε στραβά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
            header("Location: /blogspot/dashboard/tags/update-tag?tag_id=$tag_id");
            exit();
        }  

    }

?>
<?php include_once '../../includes/header.php';?>

<h1 class="page-title">Update Tag Page</h1>
<div class="container-form">
        <?php include_once '../../notifications/messages.php';?>
        <form action="/blogspot/dashboard/tags/update-tag?tag_id=<?php echo $tag_id; ?>" method="post">
            <label for="">Tag Name</label>
            <input type="text" name="tag_name" placeholder="write tag name..." value="<?php echo $tag['tag_name'] ?>">
            <input type="submit" value="Update">
        </form>
</div>
<?php include_once '../../includes/footer.php'; ?>