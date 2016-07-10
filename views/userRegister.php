<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="main">
    <div class="wrap">
        <div class="header">
        </div>
        <div align="center">

            <form class="vertical-form" method="post">
                <h1>Registration</h1>
                <input type="email" name="email" placeholder="Email Address" spellcheck="false" value="<?=$userEscapedEmail?>" required><br>
                <input type="text" name="login" placeholder="Username" value="<?=$userEscapedLogin?>" required><br>
                <input type="password" name="password" placeholder="Password" value="" required><br>
                <input type="submit" value="Save"><br>
                <a class="sign-in" href="login">Already have an account? Login</a>
                <br>
            </form>
        </div>
 
        <?php if (!empty($_POST) && isset($userIsAlreadyExistFlag)) 
        { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">User with such name or enail is already exist. Either provide another credentials or <a class="inner-text-href" href="login">log in</a></label>
            </form>
        </div>

        <?php 
        } 
        
        if (!empty($_POST) && isset ($userCreatedFlag)) 
        {?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="info" type="hidden" readonly><label for="info">User <?=$userEscapedLogin?> has been created. User ID is: <?=$userId?></label>
            </form>
        </div>
        <?php 
        } ?>


        <?php if (isset($passwordIsToShortFlag)) 
        {?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Password should contain at least 6 signs!</label>
            </form>
        </div>
        <?php 
        } ?>


    </div>
    <footer>
        Registration Page / 2016
    </footer>
</body>
</html>