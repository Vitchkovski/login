<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">


</head>

<body>

<div class="container">
    <h2> Edit Product | <?php echo $userName; ?> <a href='../login/index.php?action=logout'>(logout)</a>
    </h2>
    <?php if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Image you submitted is incorrect. Only
                    png, jpg and gif files not larger than 3 MB can be used.<br>
                    <a href='../myPage/index.php?action=close' class="close">(close)</a></label>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($incorrectProductNameFlag)) { ?>
        <div align="center">
            <form class="vertical-form-bottom">
                <input id="error" type="hidden" readonly><label for="error">Product Name can not be null<br>
                    <a href='../myPage/index.php?action=close' class="close">(close)</a></label>
            </form>
        </div>

    <?php } ?>


    <form enctype="multipart/form-data" role="form" method="post" action="../myPage/">
        <div class="form-group">
            <div class="hide-upload-btn-div">
                <img
                    src="<?php if (is_null($productInfo[0]->product_img_name)) {
                        ?>../media/products/upload-32.png<?php
                    } else {
                        ?>../uploads/<?= $userId ?>/cropped/<?= $productInfo[0]->product_img_name ?><?php
                    } ?>">

                <input type="hidden" name="initialProductPictureName"
                       value="<?= $productInfo[0]->product_img_name ?>">
                <input class="hide-upload-button-input" type="file" name="productPicture" id="file"
                       size="1">
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
        $i = 0;
        foreach ($productCategories[$productInfo[0]->product_id] as $pC):

            if (!is_null($pC)) {

                if (!is_null($pC->category_name) && $pC->category_name != "") { ?>
                    <div class="form-group">
                        <label for="productCategoriesArray[]">Category:</label>
                        <input type="text" class="form-control" size="38" name="productCategoriesArray[]"
                               value="<?= escapeSpecialCharactersHTML($pC->category_name) ?>" maxlength="255">
                    </div>
                <?php $i++;
                }
            }
        endforeach; echo $i; ?>

        <?php for ($i; $i < $categoryCounter; $i++) { ?>
            <div class="form-group">
                <label for="productCategoriesArray[]">Category:</label>
                <input type="text" class="form-control" name="productCategoriesArray[]"
                       placeholder="Enter product category"
                       maxlength="1000" value="<?= $productCategoriesArray[$i]?>">
                <input type="hidden" name="newUserProductSubmitted" value="true">
            </div>
        <?php } ?>

        <input type="hidden" name="categoryCounter" value="<?=$i+1?>">
        <input type="hidden" name="product_id" value="<?= $productInfo[0]->product_id ?>">

        <input class="btn btn-default" name="addEditCategory" type="submit" value="Add Category">
        <input class="btn btn-success" name="updateProduct" type="submit" value="Save">


    </form>
</div>


</body>

</html>
