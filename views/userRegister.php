<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">


    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Registration</h2>

        <input type="email" class="form-control" name="email" placeholder="Email Address" spellcheck="false"
               value="<?= escapeSpecialCharactersHTML($userEscapedEmail) ?>" required maxlength="254">

        <input type="text" class="form-control" name="login" placeholder="Username"
               value="<?= escapeSpecialCharactersHTML($userEscapedLogin) ?>" required maxlength="254">

        <input type="password" class="form-control" name="password" placeholder="Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
        <div align="center">
            <a class="sign-in" href="login">Already have an account? Login</a>
        </div>
        <br>
    </form>


    <?php if (!empty($_POST) && isset($userIsAlreadyExistFlag)) { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">User with such name or email is already
                    exist. Either provide another credentials or <a class="inner-text-href" href="login">log
                        in</a></label>
            </form>
        </div>

        <?php
    } ?>

    <?php if (isset($passwordIsToShortFlag)) {
        ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Password should contain at least 6
                    signs!</label>
            </form>
        </div>
        <?php
    } ?>


</div>

</body>
</html>