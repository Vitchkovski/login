<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Enter</h2>
        <input type="email" class="form-control" name="email" placeholder="Email Address" spellcheck="false"
               value="<?php if (!empty($_POST['email'])) echo escapeSpecialCharactersHTML($_POST['email']); ?>" required maxlength="254">
        <input type="password" class="form-control" name="password" placeholder="Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
        <div align="center">
            <a class="sign-in" href="<?php echo base_url("index.php/users/register"); ?>">Register a new account</a>
        </div>
        <br>
    </form>


    <?php if (!empty($_POST) && isset($credentialsAreIncorrectFlag)) { ?>
        <div class="alert alert-danger">
            <a href="<?php echo base_url("index.php/users/login"); ?>" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            Credentials you entered are incorrect</a>
        </div>
        <?php
    } ?>

</div>
