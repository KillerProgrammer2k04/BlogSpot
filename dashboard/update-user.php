<?php

    session_start();
    require_once '../db/config.php';

    if(!isset($_SESSION['user_id']))
    {
        header("Location: /blogspot/index");
        exit();
    }

    if(isset($_GET['user_id']))
    {
        $user_id = $_GET['user_id'];

        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();
    }


    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $user_image = $_FILES['user_image']['name'];
        $tmp_image = $_FILES['user_image']['tmp_name'];



        $sqlUsername = "SELECT * FROM users WHERE username = :username AND user_id != :user_id";
        $stmtUsername = $conn->prepare($sqlUsername);
        $stmtUsername->bindParam(':username', $username);
        $stmtUsername->bindParam(':user_id', $user_id);
        $stmtUsername->execute();
        $ExistUsername = $stmtUsername->fetchColumn();

        $sqlemail = "SELECT * FROM users WHERE email = :email AND user_id != :user_id";
        $stmtemail = $conn->prepare($sqlemail);
        $stmtemail->bindParam(':email', $email);
        $stmtemail->bindParam(':user_id', $user_id);
        $stmtemail->execute();
        $Existemail = $stmtemail->fetchColumn();

        if(empty($username) || empty($firstname) || empty($lastname) || empty($email))
        {
            $errorMessage = "Όλα τα πεδία είναι υποχρεωτικά!";
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errorMessage = "To email δεν είναι έγκυρο";
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        elseif($ExistUsername)
        {
            $errorMessage = "To username υπάρχει ήδη!";
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        elseif($Existemail)
        {
            $errorMessage = "To email υπάρχει ήδη!";
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else
        {


            if (!empty($user_image)) 
            {
                $uploadDir = "../uploads/$username";
    
                if(!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
    
                $filepath = "$uploadDir/$user_image";
                move_uploaded_file($tmp_image, $filepath);
    
                $InsertSql = "UPDATE users SET 
                        username = :username, firstname = :firstname,
                        lastname = :lastname, email = :email, user_image = :user_image
                        WHERE user_id = :user_id";
                $stmtInsert = $conn->prepare($InsertSql);
                $stmtInsert->bindParam(':user_image', $user_image);
            } 
            else 
            {
                $InsertSql = "UPDATE users SET 
                        username = :username, firstname = :firstname,
                        lastname = :lastname, email = :email
                        WHERE user_id = :user_id";
                $stmtInsert = $conn->prepare($InsertSql);
            }
    
            $stmtInsert->bindParam(':username', htmlspecialchars($username));
            $stmtInsert->bindParam(':firstname', htmlspecialchars($firstname));
            $stmtInsert->bindParam(':lastname', htmlspecialchars($lastname));
            $stmtInsert->bindParam(':email', htmlspecialchars($email));
            $stmtInsert->bindParam(':user_id', $user_id);
    
            if($stmtInsert->execute())
            {
                $_SESSION['SuccessMessage'] = 'Οι αλλαγές έγιναν με επιτυχία!';
                header("Location:/blogspot/dashboard/update-user.php?user_id=$user_id");
                exit();
            }
            else
            {
                $_SESSION['ErrorMessage'] = "Κάτι πήγε στραβά. Ξαναπροσπαθήστε αργότερα!";
                header("Location:/blogspot/dashboard/update-user.php?user_id=$user_id");
                exit();
            }     

        }
    }


?>
<?php include_once '../includes/header.php';?>

<h1 class="page-title">Update Page</h1>

<div class="container-form">
        <?php include_once '../notifications/messages.php';?>
        <form action="/blogspot/dashboard/update-user.php?user_id=<?php echo $user_id?>" method="post" enctype="multipart/form-data">
            <label for="">Username</label>
            <input type="text" name="username" placeholder="write username..." value="<?php echo $user['username']; ?>">
            <label for="">firstname</label>
            <input type="text" name="firstname" placeholder="write firstname..." value="<?php echo $user['firstname']; ?>">
            <label for="">lastname</label>
            <input type="text" name="lastname" placeholder="write lastname..." value="<?php echo $user['lastname']; ?>">
            <label for="">email</label>
            <input type="text" name="email" placeholder="example@gmail.com" value="<?php echo $user['email']; ?>">
            <label for="">Upload Image</label>
            <?php if($user['user_image']) : ?> 
            <img width="100px" src="/blogspot/uploads/<?php echo $user['username']; ?>/<?php echo $user['user_image']; ?>" alt="user_image">
            <?php else: ?>
                <img width="100px" src="/blogspot/uploads/noimage/user_icon.png" alt="user_image">
            <?php endif; ?>
            <input type="file" name="user_image">
            <input type="submit" value="Update">
        </form>
</div>

<?php include_once '../includes/footer.php';?>
