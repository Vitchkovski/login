<th width="50">
    <form enctype="multipart/form-data" method="post" action="../myPage/">
        <div class="hide-upload-btn-div">
            <div><img src="../media/products/upload-32.png"></div>
            <input type="hidden" name="initialProductPictureName"
                   value="<?= $uP->product_img_name ?>">
            <input class="hide-upload-button-input" type="file" name="productPicture" id="file"
                   size="1">
        </div>
        <!--<input name="productPicture" type="file"></th>-->
<th width="200"><input type="text" name="productName"
                       value="<?= escapeSpecialCharactersHTML($uP->product_name) ?>"
                       placeholder="Product Name" autofocus required maxlength="254"></th>


<th width="300"><input type="text" size="38" name="productCategoriesString"
                       value="<?php
                       $lastCategoryElement = end($productCategories[$uP->product_id]);
                       foreach ($productCategories[$uP->product_id] as $pC):
                           if (!is_null($pC)) {

                               if (!is_null($pC->category_name) && $pC->category_name != "") {
                                   echo escapeSpecialCharactersHTML($pC->category_name);
                                   if ($lastCategoryElement->category_id != $pC->category_id && count($productCategories[$uP->product_id]) > 2)
                                       echo ", ";
                               }
                           }
                       endforeach; ?>"

                       placeholder="Product Categories"
                       maxlength="1000">

    <input type="hidden" name="updateUserProductString" value="true"></th>
<input type="hidden" name="product_id" value="<?=$uP->product_id?>">

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