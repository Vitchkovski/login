<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="main">
    <div class="wrap">
        <div class="header">
        </div>
        <div align="center">

            <form class="vertical-form-login" method="post">
                <h1>Enter</h1>
                <input type="text" name="login" placeholder="Username" value="<?=$userEscapedLogin?>" required><br>
                <input type="password" name="password" placeholder="Password" value="" required><br>
                <input type="submit" value="Log In"><br>
                <a class="sign-in" href="../">Register a new account</a>
                <br>
            </form>
        </div>
 
        <?php if (!empty($_POST) && isset($passwordIsIncorrectFlag)) 
        { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">User you entered is already exist. Either provide a correct password or create a new user.</label>
            </form>
        </div>

        <?php 
        }
        
        if (!empty($_POST) && isset($thisIsLoggedUserFlag)) 
        {?>
        <div align="center">
            <form class="vertical-form-bottom">

                <input id="info" type="hidden" readonly><label for="info">Welcome, <?=$userEscapedLogin?>. Your ID is: <?=$userId?></label>
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
