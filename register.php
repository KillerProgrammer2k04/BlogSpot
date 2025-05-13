<?php

    session_start();

    require_once 'db/config.php';

    $errorMessage = "";
    $successMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {

        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = 'subscriber';
        $user_image = $_FILES['user_image']['name'];
        $tmp_user_image = $_FILES['user_image']['tmp_name'];

        $sqlUsername = "SELECT * FROM users WHERE username = :username";
        $stmtUsername = $conn->prepare($sqlUsername);
        $stmtUsername->bindParam(':username', $username);
        $stmtUsername->execute();
        $ExistUsername = $stmtUsername->fetchColumn();

        $sqlemail = "SELECT * FROM users WHERE email = :email";
        $stmtemail = $conn->prepare($sqlemail);
        $stmtemail->bindParam(':email', $email);
        $stmtemail->execute();
        $Existemail = $stmtemail->fetchColumn();

        if(empty($username))
        {
            $errorMessage = 'Το όνομα του χρήστη είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if($ExistUsername)
        {
            $errorMessage = 'Το όνομα του χρήστη υπάρχει ήδη';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(empty($firstname))
        {
            $errorMessage = 'Το όνομα είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(empty($lastname))
        {
            $errorMessage = 'Το επίθετο είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(empty($email))
        {
            $errorMessage = 'Το email είναι υποχρεωτικό!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errorMessage = 'Το email δεν είναι έγκυρο';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if($Existemail)
        {
            $errorMessage = 'Το email υπάρχει ήδη';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(empty($password))
        {
            $errorMessage = 'Ο κωδικός είναι υποχρεωτικός!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else if(empty($user_image))
        {
            $errorMessage = 'Η εικόνα είναι υποχρεωτική!';
            $_SESSION['ErrorMessage'] = $errorMessage;
        }

        else
        {

            $upload = "uploads/$username";

            if(!is_dir($upload))
            {
                mkdir($upload, 0777, true);
            }

            $filepath = "$upload/$username";

            if(move_uploaded_file($tmp_user_image, $filepath))
            {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (username, firstname, lastname, role, email, password, user_image)
                        VALUES (:username, :firstname, :lastname, :role, :email, :password, :user_image)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', htmlspecialchars($username));            
                $stmt->bindParam(':firstname', htmlspecialchars($firstname));
                $stmt->bindParam(':lastname', htmlspecialchars($lastname));
                $stmt->bindParam(':role', htmlspecialchars($role));
                $stmt->bindParam(':email', htmlspecialchars($email));
                $stmt->bindParam(':password', htmlspecialchars($hashedPassword));
                $stmt->bindParam(':user_image', $user_image);
                if($stmt->execute())
                {
                    $successMessage = "Η εγγραφή έγινε με επιτυχία!";
                    $_SESSION['SuccessMessage'] = $successMessage;
                    header("Location: /blogspot/register");
                    exit();
                }
                else
                {
                    $errorMessage = "Κάτι πήγε στραβά!";
                    $_SESSION['ErrorMessage'] = $errorMessage;
                    header("Location: /blogspot/register");
                    exit();
                }        

            }

            else
            {
                $errorMessage = "Κάτι πήγε στραβά με το ανέβασμα της εικόνας!";
                $_SESSION['ErrorMessage'] = $errorMessage;
            }
        }
}

?>
<?php include_once 'includes/header.php';?>

<h1 class="page-title">Register Page</h1>
<?php if(!isset($_SESSION['user_id'])) : ?>
    <div class="container-form">
        <?php include_once 'notifications/messages.php';?>
        <form action="/blogspot/register" method="post" enctype="multipart/form-data">
            <label for="">Username</label>
            <input type="text" name="username" placeholder="write username..." value="<?php echo isset ($_POST['username']) ? htmlspecialchars($_POST['username']) : "" ;?>">
            <label for="">firstname</label>
            <input type="text" name="firstname" placeholder="write firstname..." value="<?php echo isset ($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : "" ;?>">
            <label for="">lastname</label>
            <input type="text" name="lastname" placeholder="write lastname..." value="<?php echo isset ($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : "" ;?>">
            <label for="">email</label>
            <input type="text" name="email" placeholder="example@gmail.com" value="<?php echo isset ($_POST['email']) ? htmlspecialchars($_POST['email']) : "" ;?>">
            <label for="">password</label>
            <input type="password" name="password" placeholder="write password..." value="<?php echo isset ($_POST['password']) ? htmlspecialchars($_POST['password']) : "" ;?>">
            <label for="">Upload Image</label>
            <input type="file" name="user_image">
            <input type="submit" value="Register">
        </form>
    </div>
    <?php else: ?>
    <p> Είστε ήδη συνδεδεμένος! </p>
<?php endif; ?>
<?php include_once 'includes/footer.php'; ?>