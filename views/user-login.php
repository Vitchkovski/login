<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Vit</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="wrap">
        <div class="header">
            <h1>Registration</h1>
        </div>
        <div>
            <form class="vertical-form" method="post">

                <input type="email" name="email" placeholder="Email Address" spellcheck="false" value="<?=$email?>" required>



                <input type="text" name="login" placeholder="Login" value="<?=$login?>" required>



                <input type="password" name="pass" placeholder="Password" value="" required>


                <input type="submit" value="Save"><br>

                <?php if (!empty($_POST) && $id == 0) { ?>


                <input id="error" type="hidden" readonly><label for="error">User you entered is already exist. Either provide a correct password or create a new user.</label>


                <?php }
                
                if (!empty($_POST) && $id !== 0 && $logged_user_flag == 1) {?>


                <input id="info" type="hidden" readonly><label for="info">Welcome, <?=$login?>. Your ID is: <?=$id?></label>



                <?php } 
                if (!empty($_POST) && $id !== 0 && $logged_user_flag == 0) {?>

                <input id="info" type="hidden" readonly><label for="info">User <?=$login?> has been created. User ID is: <?=$id?></label>

                <?php } ?>


            </form>
        </div>
    </div>
        <footer>
        Registration Page / 2016
    </footer>
</body>
</html>
