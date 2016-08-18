<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Registration</h2>

        <input type="email" class="form-control" name="email" placeholder="Email Address" spellcheck="false"
               value="<?php if (!empty($_POST['email'])) echo escapeSpecialCharactersHTML($_POST['email']); ?>" required maxlength="254">

        <input type="text" class="form-control" name="login" placeholder="Username"
               value="<?php if (!empty($_POST['login'])) echo escapeSpecialCharactersHTML($_POST['login']); ?>" required maxlength="254">

        <input type="password" class="form-control" name="password" placeholder="Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
        <div align="center">
            <a class="sign-in" href="<?php echo base_url(); ?>index.php/login">Already have an account? Login</a>
        </div>
        <br>
    </form>


    <?php if (!empty($_POST) && isset($userIsAlreadyExistFlag)) { ?>
        <div class="alert alert-danger">
            <a href="<?php echo base_url(); ?>index.php/main" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            User with such email is already exist.</a>
        </div>

        <?php
    } ?>

    <?php if (isset($passwordIsToShortFlag)) {
        ?>
        <div class="alert alert-danger">
            <a href="<?php echo base_url(); ?>index.php/main" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            Password should contain at least 6 signs!</a>
        </div>
        <?php
    } ?>


</div>
