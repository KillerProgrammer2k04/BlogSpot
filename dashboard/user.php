<?php

    session_start();

    require_once '../db/config.php';

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $user = $stmt->fetch();

?>
<?php include_once '../includes/header.php';?>

<h1 class="page-title">Dashboard Page</h1>

<div class="container">
    <div class="col">
        <h2>Username</h2>
        <p><?php echo $user['username']; ?></p>
    </div>
    <div class="col">
        <h2>Role</h2>
        <p><?php echo $user['role']; ?></p>
    </div>
    <div class="col">
        <h2>Email</h2>
        <p><?php echo $user['email']; ?></p>
    </div>
    <div class="col">
        <h2>FirstName</h2>
        <p><?php echo $user['firstname']; ?></p>
    </div>
    <div class="col">
        <h2> LastName </h2>
        <p><?php echo $user['lastname']; ?></p>
    </div>
    <div class="col">
        <h2>Image</h2>
        <?php if($user['user_image']) : ?> 
        <img width="100px" src="/blogspot/uploads/<?php echo $user['username']; ?>/<?php echo $user['user_image']; ?>" alt="user_image">
        <?php else: ?>
            <img width="100px" src="/blogspot/uploads/noimage/user_icon.png" alt="user_image">
        <?php endif; ?>
    </div>
    <div class="col center">
        <button><a href="/blogspot/dashboard/update-user.php?user_id=<?php echo $user['user_id']; ?>">Update</a></button>
    </div>
    <div class="col center">
        <button><a href="/blogspot/dashboard/update-password.php?user_id=<?php echo $user['user_id']; ?>">Change Password</a></button>
    </div>
</div>


<?php include_once '../includes/footer.php'; ?>