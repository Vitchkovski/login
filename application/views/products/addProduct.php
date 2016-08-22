<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <h2> Add New Product to the List | <?php echo $this->session->userdata('userSessionName'); ?></h2>


    <?php if (isset($incorrectProductNameFlag)) { ?>


        <div class="alert alert-danger">
            <a href="<?php echo base_url("index.php/products/addProduct"); ?>" class="close" data-dismiss="alert"
               aria-label="close">&times;</a>
            Product Name can not be null.
        </div>


    <?php } ?>

    <form enctype="multipart/form-data" role="form" method="post"
          action="<?php echo base_url("index.php/products/addProduct"); ?>">
        <div class="form-group">
            <label for="productPicture">Product Picture</label>
            <input name="productPicture" type="file">
        </div>
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" class="form-control" placeholder="Enter product name" autofocus
                   maxlength="254" value="<?php echo set_value('productName'); ?>">
        </div>

        <div id="add_field_area">
            <div id="category1" class="form-group">
                <label for="productCategoriesArray[]">Category:</label>
                <input type="text" class="form-control" name="productCategoriesArray[]"
                       placeholder="Enter product category"
                       maxlength="1000" value="">
            </div>
        </div>


        <div align="right">
            <a class="btn btn-warning" onclick="addField();">Add New Category</a>
            <input class="btn btn-success" name="saveProduct" type="submit" value="Save">
            <a class="btn btn-danger" href="<?php echo base_url("index.php/products"); ?>">Cancel</a>
        </div>
    </form>
    <p><?php echo validation_errors('<p class="alert alert-danger">'); ?></p>
</div>
