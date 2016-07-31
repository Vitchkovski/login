<th width="50">
    <form enctype="multipart/form-data" method="post" action="../myPage/">
        <div class="hide-upload-btn-div">
            <img
                src="<?php if (is_null($uP->product_img_name)) {
                    ?>../media/products/upload-32.png<?php
                } else {
                    ?>../uploads/<?= $userId ?>/cropped/<?= $uP->product_img_name ?><?php
                } ?>">

            <input type="hidden" name="initialProductPictureName"
                   value="<?= $uP->product_img_name ?>">
            <input class="hide-upload-button-input" type="file" name="productPicture" id="file"
                   size="1">
        </div>
        <!--<input name="productPicture" type="file"></th>-->
<th width="200"><input type="text" name="productName"
                       value="<?= escapeSpecialCharactersHTML($uP->product_name) ?>"
                       placeholder="Product Name" autofocus required maxlength="254"></th>


<th width="300">
    <form method="post" action="../myPage/">
        <?php foreach ($productCategories[$uP->product_id] as $pC):
            if (!is_null($pC)) {
                if (!is_null($pC->category_name) && $pC->category_name != "") { ?>
                    <input type="text" size="38" name="productCategoriesArray[]"
                           value="<?= escapeSpecialCharactersHTML($pC->category_name) ?>" maxlength="255">
                <?php }
            }
        endforeach; ?>
        <input type="text" size="38" name="productCategoriesArray[]"
               placeholder="Category" maxlength="255">

        <?php
        if (!empty($_POST['addCategoryFlag'])) { ?>
            <input type="text" size="38" name="productCategoriesArray[]"
                   placeholder="Category" maxlength="255">
        <?php } ?>
        <input type="hidden" name="addCategoryFlag" value="true">
        <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
        <input type="submit" value="Add Category">


        <input type="hidden" name="updateUserProductString" value="true">
</th>
<input type="hidden" name="product_id" value="<?= $uP->product_id ?>">

<th width="66" align="right"><input TYPE="image" SRC="../media/products/save-32.png"
                                    HEIGHT="24"
                                    WIDTH="24" BORDER="0" ALT="Save"></th>
</form>

<th width="32" align="right">
    <form action="../myPage/" method="post">
        <input type="hidden" name="cancelEditModeFlag" value="true">
        <input TYPE="image" SRC="../media/products/cancel-32.png" HEIGHT="24" WIDTH="24"
               BORDER="0" ALT="Cancel">
    </form>
</th>