<!doctype html>
<html lang="ru">
    <head>
    <meta charset="utf-8" />
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css" />
    </head>
    <style>
        body {
            font-family: sans-serif;
        }
        
        a {
            text-decoration: none;
        }

        .info {
            margin-left: 20px;
            margin-top: 50px;
        }

        .info div {
            font-size: 18px;
            line-height: 1.7;
        }

        .info a {
            display: inline-block;
            margin-top: 30px;
            font-size: 24px;

            color: darkslateblue;
        }

        .info span {
            display: inline-block;
            width: 150px;
        }
        
        header {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        header h1 {
            margin: 0;
            justify-self: center;
        }

        header a {
            justify-self: end;
        }

        .avatar {
            width: 90px; 
            height: 90px;
            border-radius: 100%;
        }

        .myposts {
            margin-left: 20px;
        }

        .post {
            width: 400px;
            border-radius: 10px;

            color: white;
            background-color: gray;
            margin-bottom: 10px;
        }

        .title {
            padding: 10px 10px 0 10px;
            font-size: 20px;
        }

        .text {
            font-size: 18px;
            padding: 10px 10px 10px 10px;
        }

        .edit {
            padding: 0 10px 10px;
            display: flex;
            justify-content: space-between;
        }

        .deletebutton {
            padding-right: 20px;
            color: lightpink;
        }

        .editbutton {
            color: lightblue;
        }
    </style>
    <header>
        <div><a href="/">Home</a></div>
        <h1>My Profile</h1>
        <div></div>
    </header>
    <body>
        <div class="info">
            <?php
                if($avatar) {
                    echo '<img class="avatar" src="' . '/storage/profile/avatar/' . htmlspecialchars($avatar) . '">';
                }
            ?>
            <div><span>username: </span><?= $username ?></div>
        </div>

        <div class="myposts">
            <h2><?= $username . "'s posts:" ?> </h2>
            <?php
                foreach(array_reverse($posts) as $post):
            ?>
                    <div class="post">
                        <div class="title"><?= $post['title'] ?></div>
                        <div class="text"><?= implode(' ', array_slice(explode(' ', $post['content']), 0, 10)) . '...' ?></div>
                        <div class="edit">
                            <a class="readbutton" href="/post?post_id=<?= $post['post_id'] ?>">Read</a> 
                        </div>
                    </div>
                    
            <?php
                endforeach;
            ?>
        </div>
    </body>
</html>