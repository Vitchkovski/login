<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" action="<?php echo base_url("index.php/users/reset_password"); ?>" method="post">
        <h2 class="form-signin-heading">Reset Password</h2>
        <input type="email" class="form-control" name="email" placeholder="Enter Your Email" spellcheck="false"
               value="<?php echo set_value('email'); ?>" required maxlength="254" style="margin-bottom: 10px">

        <button class="btn btn-lg btn-primary btn-block" type="submit">Reset My Password</button>
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
