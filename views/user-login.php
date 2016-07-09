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
        <div align = "center">
            
            <form class="vertical-form" method="post">
                <h1>Registration</h1>

                <input type="email" name="email" placeholder="Email Address" spellcheck="false" value="<?=$email?>" required><br>



                <input type="text" name="login" placeholder="Login" value="<?=$login?>" required><br>



                <input type="password" name="pass" placeholder="Password" value="" required><br>


                <input type="submit" value="Save"><br>
            </form>
        </div>
                <?php if (!empty($_POST) && $id == 0 && !isset($incorrect_pass)) { ?>
        <div align = "center">
            <form class="vertical-form-bottom">


                <input id="error" type="hidden" readonly><label for="error">User you entered is already exist. Either provide a correct password or create a new user.</label>
            </form>
        </div>

                <?php }
                
                if (!empty($_POST) && $id !== 0 && $logged_user_flag == 1) {?>
        <div align = "center">
            <form class="vertical-form-bottom">

                <input id="info" type="hidden" readonly><label for="info">Welcome, <?=$login?>. Your ID is: <?=$id?></label>
            </form>
        </div>


                <?php } 
                if (!empty($_POST) && $id !== 0 && $logged_user_flag == 0) {?>
        <div align = "center">
            <form class="vertical-form-bottom">
                <input id="info" type="hidden" readonly><label for="info">User <?=$login?> has been created. User ID is: <?=$id?></label>
            </form>
        </div>
                <?php } ?>
        
        
                <?php if (isset($incorrect_pass)) {?>
        <div align = "center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Password should contain at least 6 signs!</label>
            </form>
        </div>
                <?php } ?>


            
    
    </div>
        <footer>
        Registration Page / 2016
    </footer>
</body>
</html>
