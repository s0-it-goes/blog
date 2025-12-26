<!doctype html>
<html lang="ru">
    <head>
    <meta charset="utf-8" />
    <title>Registration</title>
    <link rel="stylesheet" href="style.css" />
    </head>
    <style>
        body {
            font-family: sans-serif;
        }
        
        a {
            text-decoration: none;
        }
        
        div {
            text-align: center;
            margin-top: 150px;
        }
    </style>
    <body>
        <div class="register">
            <?php 
                foreach($flash as $error):
                    if($error !== null):
            ?>
                <h2 style="color: red"><?= $error ?></h2> <br>
            <?php 
                    endif;
                endforeach;
            ?>
            <form method="POST">
                <input type="text" name="login" placeholder="login"><br>
                <input type="email" name="email" placeholder="email"><br>
                <input type="password" name="password" placeholder="password"><br>
                <button type="submit">confirm registration</button>
            </form>
            <h2 style="margin-top: 30px">Already has an account?</h2>
            <h2><a href="/authorization">Log in</a></h2>
        </div>
    </body>
</html>