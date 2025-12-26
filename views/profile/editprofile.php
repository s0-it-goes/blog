<!doctype html>
<html lang="ru">
    <head>
        <link rel="stylesheet" href="/css/main.css"/>
        <link rel="stylesheet" href="/css/profile/edit.css"/>
        <link rel="stylesheet" type="text/css" href="/css/theme.css">
        <meta charset="utf-8" />
        <title>Change profile</title>
    </head>

    <header>
            <header>
        <div class="left"><a href="/profile">Back to profile</a></div>
        <h1>Change profile</h1>

        <div class="right">
            <div>
                <span class="themeButton" style="cursor:pointer" data-theme="light">☀️</span>
                <span class="themeButton" style="cursor:pointer" data-theme="dark">🌕</span>
            </div>
        </div>
    </header>
    </header>
    <body class = "<?= $_SESSION['theme'] ?? 'light' ?>">
        <div class="info">
            <?php 
                foreach($flash as $error):
                    if($error !== null):
                        echo '<h2 style="color: red">' . $error . '</h2>';
            ?>
                
            <?php 
                    endif;
                endforeach;
            ?>
            
            <form class="userDataForm" method="post" enctype="multipart/form-data">
                <div class="userData">
                    <div class="usernameForm">
                        <h3>Username:</h3>
                        <div><span>Current username: </span><?= $username ?> </div>
                        <div><span>New username: </span><input type="text" name="login" placeholder="new username"></div>
                    </div>

                    <div class="emailForm">
                        <h3>Email:</h3>
                        <div><span>Current email: </span><?= $email ?></div>
                        <div><span>New Email: </span><input type="email" name="email" placeholder="new email"></div>
                    </div>

                    <div class="passwordForm">
                        <h3>Password:</h3>
                        <div><span>Old Pasword: </span><input type="text" name="oldpassword" placeholder="old password"></div>
                        <div><span>New password: </span><input type="text" name="newpassword1" placeholder="new password"></div>
                        <div><span>New password: </span><input type="text" name="newpassword2" placeholder="new password"></div>
                    </div>
                </div>

                <div class="userAvatar">
                    <h3>Avatar:</h3>
                    <?php
                        if($avatar) {
                            echo '<img class="avatar" src="' . '/storage/profile/avatar/' . htmlspecialchars($avatar) . '">';
                        }
                    ?>
                    <br>
                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg, .gif"> <br>

                </div>
                <button type="submit">Confirm Edits</button>
            </form>

            <form class="signOutForm" action="/profile/logout" method="post">
                <button type="submit" name="logout">Sign out</button>
            </form>
        </div>

        <script src="/js/theme.js"></script>
    </body>

    <footer>

    </footer>
</html>