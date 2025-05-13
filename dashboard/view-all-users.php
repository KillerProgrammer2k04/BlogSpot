<?php

    session_start();

    require_once '../db/config.php';

    $sqlusers = "SELECT * FROM users";
    $stmt = $conn->prepare($sqlusers);
    $stmt->execute();
    $users = $stmt->fetchAll();

?>
<?php include_once '../includes/header.php';?>

<h1 class="page-title">View Users Page</h1>
<div class="container-table">
    <table>
        <tr>
            <th> User ID </th>
            <th> Username </th>
            <th> Firstname </th>
            <th> Lastname </th>
            <th> Email </th>
            <th> User Image </th>
            <th> Role </th>
            <th> Update Informations</th>
            <th> Change Password</th>
            <th> Delete </th>
        </tr> 
        <?php foreach($users as $user): ?> 
        <tr>
            <td><?php echo $user['user_id'];?></td>
            <td><?php echo $user['username'];?></td>
            <td><?php echo $user['firstname'];?></td>
            <td><?php echo $user['lastname'];?></td>
            <td><?php echo $user['email'];?></td>
            <td>
                <?php if($user['user_image']) : ?> 
                <img width="100px" src="/blogspot/uploads/<?php echo $user['username']; ?>/<?php echo $user['user_image']; ?>" alt="user_image">
                <?php else: ?>
                    <img width="100px" src="/blogspot/uploads/noimage/user_icon.png" alt="user_image">
                <?php endif; ?>
            </td>
            <td><?php echo $user['role']?></td>
            <td><a href="/blogspot/dashboard/update-user.php?user_id=<?php echo $user['user_id']; ?>">Update</a></td>
            <td><a href="/blogspot/dashboard/changepassword.php?user_id=<?php echo $user['user_id']; ?>">Change</a></td>
            <td><a href="/blogspot/dashboard/delete-user.php?user_id=<?php echo $user['user_id']; ?>">Delete</a></td>
        </tr> 
        <?php endforeach; ?>
    </table>
</div>

<?php include_once '../includes/footer.php'; ?>