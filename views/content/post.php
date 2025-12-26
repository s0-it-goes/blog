<!doctype html>
<html lang="ru">
    <head>
        <link rel="stylesheet" type="text/css" href="/css/main.css">
        <link rel="stylesheet" type="text/css" href="/css/content/post.css">
        <link rel="stylesheet" type="text/css" href="/css/theme.css">
        
        <meta charset="utf-8" />
        <title><?= $post['title'] ?></title>
    </head>

    <header>

        <div class="left"><a class="create_post" href="/posts/create">Create Post</a></div>
        <div></div>
        <div class="right">
            <div>
                <span class="themeButton" style="cursor:pointer" data-theme="light">☀️</span>
                <span class="themeButton" style="cursor:pointer" data-theme="dark">🌕</span>
            </div>
            <a class="profile" href="/profile"><?= $avatar ? '<img class="avatar" src="/storage/profile/avatar/' . htmlspecialchars($avatar) . '">' : 'My Profile'?></a>
        </div>

    </header>

    <body class="<?= $_SESSION['theme'] ?? 'light' ?>">
        <div class="content">
            <h1><?= $post['title'] ?></h1>
            <hr size="1">
            <div class="content">
                <?= $post['content'] ?>
            </div>

            <hr size="1">

            <h2>Comments</h2>
            
            <?= $flash['comment'] ? '<h2 style="color: orange">comment cannot be empty</h2>' : '' ?>  

            <div class="comments">
                <form method="post">
                    <div>
                        <textarea name="comment" type="text" placeholder="Commentary"></textarea>
                        <button type="submit">Send</button>
                    </div>
                </form>
                
                <div class="usersComments">
                    <?php
                        foreach(array_reverse($comments) as $comment):
                    ?> 
                            <div class='usercomment'>
                                <div>
                                    <a style="color: pink;" href="/userprofile?id=<?= $comment['user_id'] ?>"><?=$comment['username']?></a>
                                    <span><?= $comment['created_at']['date'] . ' at ' . $comment['created_at']['time'] ?></span>
                                </div>
                                <p><?= $comment['comment'] ?></p>
                            </div>
                    <?php
                        endforeach;
                    ?>
                </div>
            </div>
        </div>

        <script src="/js/theme.js"></script>
    </body>
</html>