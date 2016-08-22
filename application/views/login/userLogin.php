<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" action="<?php echo base_url("index.php/users/login"); ?>" method="post">
        <h2 class="form-signin-heading">Enter</h2>
        <input type="email" class="form-control" name="email" placeholder="Email Address" spellcheck="false"
               value="<?php echo set_value('email'); ?>" required maxlength="254">
        <input type="password" class="form-control" name="password" placeholder="Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
        <div align="center">
            <a class="sign-in" href="<?php echo base_url("index.php/users/register"); ?>">Register account</a> /
            <a class="sign-in" href="<?php echo base_url("index.php/users/reset_password"); ?>">Forgot your password?</a>
        </div>
        <br>
    </form>


    <?php if (isset($errorMsg)) {
        echo '<p class="alert alert-danger" >' . $errorMsg . '</p >';
    }
    echo validation_errors('<p class="alert alert-danger">'); ?>

    <?php if (isset($successMessage)) {
        echo '<p class="alert alert-success" >' . $successMessage . '</p >';
    }
    ?>
</div>
