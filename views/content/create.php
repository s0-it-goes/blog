<!doctype html>
<html lang="ru">
    <head>

    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/content/create.css">
    <link rel="stylesheet" type="text/css" href="/css/theme.css">

    <meta charset="utf-8" />
    <title>Create Post</title>
    </head>

    <header>

        <div class="left"><a class="home" href="/">Home</a></div>

        <h1>Create Post</h1>

        <div class="right">
            <div>
                <span class="themeButton" style="cursor:pointer" data-theme="light">☀️</span>
                <span class="themeButton" style="cursor:pointer" data-theme="dark">🌕</span>
            </div>
            <a class="profile" href="/profile"><?= $avatar ? '<img class="avatar" src="/storage/profile/avatar/' . htmlspecialchars($avatar) . '">' : 'My Profile'?></a>
        </div>

    </header>

    <body class="<?= $_SESSION['theme'] ?? 'light' ?>">
            <?php 
                foreach($flash as $error):
                    if($error !== null):
                        echo '<h2 style="color: red; text-align:center;">' . $error . '</h2>';
                    endif;
                endforeach;
            ?>
        <form method="post" enctype="multipart/form-data">
            <input class="title" name="title" type="text" placeholder="Title"><br>
            <textarea class="content" name="content" type="text" placeholder="Content"></textarea><br>
            <input class="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif"><br>
            <button type="submit" class="submitButton">Publish</button>
        </form>

        <script src="/js/theme.js"></script>
    </body>
</html>