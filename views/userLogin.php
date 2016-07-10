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
                <input type="email" name="email" placeholder="Email Address" spellcheck="false" value="<?=$userEscapedEmail?>" required><br>
                <input type="text" name="login" placeholder="Username" value="<?=$userEscapedLogin?>" required><br>
                <input type="password" name="password" placeholder="Password" value="" required><br>
                <input type="submit" value="Log In"><br>
                <a class="sign-in" href="../">Register a new account</a>
                <br>
            </form>
        </div>
 
        <?php if (!empty($_POST) && isset($credentialsAreIncorrectFlag)) 
        { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Credentials you entered are incorrect</label>
            </form>
        </div>

        <?php 
        }?>
        

    </div>
    <footer>
        Login Page / 2016
    </footer>
</body>

</html>
