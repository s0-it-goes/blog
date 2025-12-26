<!doctype html>
<html lang="ru">
    <head>
    <meta charset="utf-8" />
    <title>Authorization</title>
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
            margin-top: 200px;
        }
    </style>
    <body>
        <div class="autorization">
            <?php 
                foreach($flash as $error):
                    if($error !== null):
            ?>
                <h2 style="color: red"><?= $error ?></h2> <br>
            <?php 
                    endif;
                endforeach;
            ?>
            <form action ="/authorization" method="POST">
                <input type="text" name="login" style="margin-bottom: 15px" placeholder="login"><br>
                <input type="password" name="password" placeholder="password"><br>
                <button type="submit" style="margin-top:15px">log in</button>
            </form>
            <h2 style="margin-top: 30px">Have no account?</h2>
            <h2><a href="/registration">Registration</a></h2>
        </div>
    </body>
</html>