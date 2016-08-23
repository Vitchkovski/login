<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">
    <h2> Edit Product | <?php echo $this->session->userdata('userSessionName'); ?></h2>

    <form enctype="multipart/form-data" role="form" method="post"
          action="<?php echo base_url("index.php/products/editProduct/"); ?><?= $productInfo[0]->product_id ?>">
        <div class="form-group">
            <div class="hide-upload-btn-div">
                <label for="productPicture">Product Picture:</label>
                <br><?php if (!is_null($productInfo[0]->product_img_name)) { ?>
                    <a href="<?php echo base_url("assets/img/uploads/"); ?><?= $userId ?>/original/<?= $productInfo[0]->product_img_name ?>"
                       target="_blank">
                        <img class="img-rounded" style="margin-bottom: 3px"
                             src="<?php echo base_url("assets/img/uploads/"); ?><?= $userId ?>/cropped/<?= $productInfo[0]->product_img_name ?>"></a>
                <?php } ?>

                <input type="hidden" name="initialProductPictureName"
                       value="<?= $productInfo[0]->product_img_name ?>">
                <input type="file" name="productPicture" id="file">
            </div>
        </div>
        <div class="form-group">
            <!--<input name="productPicture" type="file"></th>-->
            <label for="productName">Product Name:</label>
            <input type="text" name="productName"
                   value="<?= escapeSpecialCharactersHTML($productInfo[0]->product_name) ?>"
                   placeholder="Product Name" class="form-control" autofocus maxlength="254">

        </div>


        <?php
        //showing all existing product categories
        $i = 0;
        foreach ($productCategories[$productInfo[0]->product_id] as $pC):

            if (!is_null($pC)) {

                if (!is_null($pC->category_name) && $pC->category_name != "") { ?>
                    <div class="form-group">
                        <?php if ($i == 0) { ?><label for="productCategoriesArray[]">Category:</label><?php } ?>
                        <input type="text" class="form-control" size="38" name="productCategoriesArray[]"
                               value="<?= escapeSpecialCharactersHTML($pC->category_name) ?>" maxlength="255">
                    </div>
                    <?php $i++;
                }
            }
        endforeach;
        ?>

        <div id="add_field_area">
            <div id="category1" class="form-group">
                <?php if ($i == 0) {
                    //showing this field only if there are NO existing product categories
                    ?>
                    <label for="productCategoriesArray[]">Category:</label>
                    <input type="text" class="form-control" name="productCategoriesArray[]"
                           placeholder="Enter product category"
                           maxlength="1000" value="">
                <?php } ?>
            </div>
        </div>

        <input type="hidden" name="product_id" value="<?= $productInfo[0]->product_id ?>">
        <div align="right">
            <a class="btn btn-warning" onclick="addField();">Add New Category</a>
            <input class="btn btn-success" name="updateProduct" type="submit" value="Save">
            <a class="btn btn-danger" href="<?php echo base_url("index.php/products"); ?>">Cancel</a>
        </div>

    </form>
    <p><?php if ($this->session->flashdata('errorMsg')) {
            echo '<p class="alert alert-danger" >' . $this->session->flashdata('errorMsg') . '</p >';
        }

        echo validation_errors('<p class="alert alert-danger">'); ?></p>
</div>

