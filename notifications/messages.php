<?php if(!empty($_SESSION['ErrorMessage'])) : ?>
    <p style="color:red"><?php echo $_SESSION['ErrorMessage']; ?></p>
    <?php unset($_SESSION['ErrorMessage']); ?>
<?php endif; ?>

<?php if(!empty($_SESSION['SuccessMessage'])) : ?>
    <p style="color:green"><?php echo $_SESSION['SuccessMessage']; ?></p>
    <?php unset($_SESSION['SuccessMessage']); ?>
<?php endif; ?>