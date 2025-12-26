<!doctype html>
<html lang="ru">
    <head>
        <link rel="stylesheet" type="text/css" href="/css/main.css">
        <link rel="stylesheet" type="text/css" href="/css/home/home.css">
        <link rel="stylesheet" type="text/css" href="/css/theme.css">

        <meta charset="utf-8" />
        <title>Home Page</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    
    
    <header>

        <div class="left"><a class="create_post" href="/posts/create">Create Post</a></div>

        <h1>Home Page</h1>

        <div class="right">
            <div>
                <span class="themeButton" style="cursor:pointer" data-theme="light">☀️</span>
                <span class="themeButton" style="cursor:pointer" data-theme="dark">🌕</span>
            </div>
            <a class="profile" href="/profile"><?= $avatar ? '<img class="avatar" src="/storage/profile/avatar/' . htmlspecialchars($avatar) . '">' : 'My Profile'?></a>
        </div>

    </header>

    <body class="<?= $_SESSION['theme'] ?? 'light' ?>">

        <div id="posts-container">
            <?php foreach($posts as $post): ?>

                <div class="post">

                    <div class="title">
                        <a class="title_name" href="/post?post_id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a>
                        <a class="author_name" href="/userprofile?id=<?= $post['author_id'] ?>"><?= $post['author'] ?></a>
                    </div>

                    <div class="content">
                        <?= $post['content'] . '...' ?>
                    </div>

                    <div class="readButton">
                        <a href="/post?post_id=<?= $post['post_id'] ?>">Read more</a>
                    </div>

                </div>

            <?php endforeach;  ?>
        </div>

        <script>
            window.shownPostIds = <?= json_encode(array_column($posts, 'post_id')) ?>
         </script>

        <button id="loadMore">Load More</button>
        
        <script src="/js/theme.js"></script>
        <script src="/js/homeloadmore.js"></script>
    </body>

    <footer>
        
    </footer>
</html>