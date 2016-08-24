<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <form class="form-signin" action="<?php echo base_url("index.php/users/update_password"); ?>" method="post">
        <h2 class="form-signin-heading">Update Password</h2>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <input type="email" class="form-control" name="email" placeholder="Email Address" readonly spellcheck="false"
               value="<?php echo (isset($email)) ? $email : ''; ?>" required maxlength="254">
        <?php if (isset($emailSecureHash, $emailResetLinkCode)) {?>
            <input type="hidden" name="emailSecureHash" value="<?= $emailSecureHash ?>">
            <input type="hidden" name="emailResetLinkCode" value="<?= $emailResetLinkCode ?>">
        <?php } ?>
        <input type="password" class="form-control" name="password" placeholder="New Password" value="" required
               maxlength="254">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update My Password</button>
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
