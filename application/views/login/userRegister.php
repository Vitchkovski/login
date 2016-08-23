<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" action="<?php echo base_url("index.php/users/register"); ?>" method="post">
        <h2 class="form-signin-heading">Registration</h2>

        <input type="email" class="form-control" name="email" placeholder="Email Address" spellcheck="false"
               value="<?php echo set_value('email'); ?>" required maxlength="254">

        <input type="text" class="form-control" name="login" placeholder="Username"
               value="<?php echo set_value('login'); ?>" required maxlength="254">

        <input type="password" class="form-control" name="password" placeholder="Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
        <div align="center">
            <a class="sign-in" href="<?php echo base_url("index.php/users/login"); ?>">Already have an account? Login</a>
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
