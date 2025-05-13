<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/blogspot/css/style.css?v=1">
    <link rel="icon" href="/blogspot/assets/logo.png">
    <title>Blogspot</title>
</head>
<body>
    <div class="header">
        <div class="col center">
            <img class="logo" src="/blogspot/assets/logo.png" alt="">
        </div>
    </div>
    <div class="mobilemenu">
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </div>
    <div class="nav">
        <span id="closesidebar">&times;</span>
        <a href="/blogspot/index">Home</a>
        <a href="/blogspot/view-articles.php">View Articles</a>
        <?php if(!isset($_SESSION['user_id'])) : ?>
            <a href="/blogspot/register">Register</a>
            <a href="/blogspot/login">Login</a>
            <div class="dropdown">
                <div class="place">
                    <a href="#sports">Sports</a>
                    <img class="arrow" src="/blogspot/assets/arrow.png" alt="">
                </div>
                <div class="megamenu">
                    <div class="col">
                        <h2>Kind of Sports</h2>
                        <a href="#">Αθλητικά</a>
                        <a href="#">Basketball</a>
                        <a href="#">Volleyball</a>
                    </div>
                    <div class="col">
                        <h2>Greek teams</h2>
                        <a href="#">Osfp</a>
                        <a href="#">Paok</a>
                        <a href="#">Pao</a>
                        <a href="#">AEK</a>
                    </div>
                    <div class="col center">
                        <h2>Banner</h2>
                        <img src="/blogspot/assets/trainers.png" alt="">
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <div class="place">
                    <a href="#games">Games</a>
                    <img class="arrow" src="/blogspot/assets/arrow.png" alt="">
                </div>
                <div class="megamenu">
                    <div class="col">
                        <h2>Kind of Games</h2>
                        <a href="#">X-BOX</a>
                        <a href="#">PS5</a>
                        <a href="#">PC</a>
                    </div>
                    <div class="col">
                        <h2>Categories Games</h2>
                        <a href="#">Actions</a>
                        <a href="#">RPG</a>
                        <a href="#">Horror</a>
                        <a href="#">Sports</a>
                    </div>
                    <div class="col center">
                        <h2>Banner</h2>
                        <img src="/blogspot/assets/games.png" alt="">
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="/blogspot/dashboard/user.php">Dashboard</a>
                <?php if($_SESSION['role'] === 'admin') : ?>
                    <a href="/blogspot/dashboard/categories/insert-category">Insert Category</a>
                    <a href="/blogspot/dashboard/subcategories/insert-subcategory">Insert subCategory</a>
                    <a href="/blogspot/dashboard/tags/insert-tag">Insert Tag</a>
                    <a href="/blogspot/dashboard/articles/insert-article">Insert Articles</a>
                    <a href="/blogspot/dashboard/articles/view-articles">All Articles</a>
                    <a href="/blogspot/dashboard/view-all-users.php">View Users</a>
                <?php endif; ?>
            <a href="/blogspot/logout">Logout</a>
        <?php endif; ?>
    </div>
    