<!doctype html>
<html lang="ru">
    <head>
        <link rel="stylesheet" href="/css/main.css"/>
        <link rel="stylesheet" href="/css/profile/myprofile.css"/>
        <link rel="stylesheet" type="text/css" href="/css/theme.css">
        
        <meta charset="utf-8" />
        <title>My Profile</title>
    </head>

    <header>
        <div class="left"><a href="/">Home</a></div>
        <h1>My profile</h1>

        <div class="right">
            <div>
                <span class="themeButton" style="cursor:pointer" data-theme="light">☀️</span>
                <span class="themeButton" style="cursor:pointer" data-theme="dark">🌕</span>
            </div>
        </div>
    </header>
    
    <body class="<?= $_SESSION['theme'] ?? 'light' ?>">
        <div class ="body-container">
            
            
            <div class="posts">
                <h2>Posts: </h2>

                <div id="posts-container">
                    <?php
                        foreach($posts as $post):
                    ?>
                        <div class="post">
                            <div class="title"><a class="title_name" href="/post?post_id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a></div>
                            <div class="text"><?= $post['content'] . '...' ?></div>
                            <div class="edit">
                                <a class="editbutton" href="/posts/edit?post_id=<?= $post['post_id'] ?>">Edit</a> 
                                <a class="deletebutton" href="/posts/delete?post_id=<?= $post['post_id'] ?>">Delete</a>
                            </div>
                        </div>

                    <?php
                        endforeach;
                    ?>
                </div>

                

                <?php if($countPosts > 3): ?>
                    <button id="loadMore">Load More</button>
                <?php endif; ?>
            
            </div>

            <div class="info">
                <h2>User info: </h2>
                <?php
                    if($avatar) {
                        echo '<img class="avatar" src="' . '/storage/profile/avatar/' . htmlspecialchars($avatar) . '">';
                    }
                ?>
                
                <div>
                    <span>username: </span><?= $username ?><br>
                    <span>email: </span><?= $email ?>
                </div>
                
                <a href = "/profile/edit">Change</a>

            </div>
        </div>

        <script src="/js/theme.js"></script>
        <script src="/js/profileloadmore.js"></script>
    </body>
    
    <footer>

    </footer>
</html>