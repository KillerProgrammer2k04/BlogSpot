<?php

    session_start();

    require_once 'db/config.php';

    $errorMessage = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $UsernameOrEmail = $_POST['username_or_email'];
        $password = $_POST['password'];

        if(empty($UsernameOrEmail) || empty($password))
        {
            $errorMessage = "Όλα τα πεδία είναι υποχρεωτικά";
            $_SESSION['ErrorMessage'] = $errorMessage;
        }
        else
        {
            $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users
                                    WHERE username = :Username_Or_Email OR email = :Username_Or_Email");
            $stmt->execute([":Username_Or_Email" => $UsernameOrEmail]);
            $users = $stmt->fetch();
            if($users && password_verify($password, $users['password']))
            {
                $_SESSION['user_id'] = $users['user_id'];
                $_SESSION['username'] = $users['username'];
                $_SESSION['role'] = $users['role'];

                header("Location: /blogspot/dashboard/user.php");
                exit();
            }
            else
            {
                $errorMessage = "Το όνομα χρήστη ή ο κωδικός πρόσβασης είναι λανθασμένος";
                $_SESSION['ErrorMessage'] = $errorMessage;
            }
        }
    }

?>
<?php include_once 'includes/header.php';?>

<h1 class="page-title">Login Page</h1>

<?php if(!isset($_SESSION['user_id'])) : ?>
    <div class="container-form">
        <?php include_once 'notifications/messages.php';?>
        <form action="/blogspot/login" method="post">
            <label for="">Username Or Email</label>
            <input type="text" name="username_or_email" placeholder="write username or email..." value="<?php echo isset ($_POST['username_or_email']) ? htmlspecialchars($_POST['username_or_email']) : "" ;?>">
            <label for="">password</label>
            <input type="password" name="password" placeholder="write password..." value="<?php echo isset ($_POST['password']) ? htmlspecialchars($_POST['password']) : "" ;?>">
            <input type="submit" value="Login">
        </form>
    </div>
<?php else: ?>
    <p> Είστε ήδη συνδεδεμένος! </p>
<?php endif; ?>
<?php include_once 'includes/footer.php'; ?>