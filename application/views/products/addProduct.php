<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <h2> Add New Product to the List | <?php echo $this->session->userdata('userSessionName'); ?></h2>

    <form enctype="multipart/form-data" role="form" method="post"
          action="<?php echo base_url("index.php/products/addProduct"); ?>">
        <div class="form-group">
            <label for="productPicture">Product Picture</label>
            <input name="productPicture" type="file">
        </div>
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="text" name="productName" class="form-control" placeholder="Enter product name" autofocus
                   maxlength="254" value="<?php echo ($this->session->flashdata('productName'))
                ? $this->session->flashdata('productName')
                : set_value('productName'); ?>">
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
    <p><?php if ($this->session->flashdata('errorMsg')) {
            echo '<p class="alert alert-danger" >' . $this->session->flashdata('errorMsg') . '</p >';
        }

        echo validation_errors('<p class="alert alert-danger">'); ?></p>
</div>
