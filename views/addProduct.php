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
    <h2> Add New Product to the List | <?php echo $userName; ?></h2>


    <?php if (isset($incorrectProductNameFlag)) { ?>


            <div class="alert alert-danger">
                <a href="../myPage/index.php?action=close" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                Product Name can not be null.
            </div>


    <?php } ?>

    <form enctype="multipart/form-data" role="form" method="post" action="../myPage/">
        <div class="form-group">
            <label for="productPicture">Product Picture</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input name="productPicture" type="file">
        </div>
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" class="form-control" placeholder="Enter product name" autofocus
                   maxlength="254" value="<?=escapeSpecialCharactersHTML($productName)?>">
        </div>
        <?php for ($i=0; $i < $categoryCounter; $i++) { ?>
        <div class="form-group">
            <?php if ($i == 0){ ?><label for="productCategoriesArray[]">Category:</label><?php }?>
            <input type="text" class="form-control" name="productCategoriesArray[]"
                   placeholder="Enter product category"
                   maxlength="1000" value="<?= escapeSpecialCharactersHTML($productCategoriesArray[$i])?>">
            <input type="hidden" name="newUserProductSubmitted" value="true">
        </div>
        <?php } ?>
        <input type="hidden" name="categoryCounter" value="<?=$i+1?>">
        <div align="right">
            <input class="btn btn-default" name="addSaveCategory" type="submit" value="Add Category">
            <input class="btn btn-success" name="saveProduct" type="submit" value="Save">
            <a class="btn btn-danger" href="../myPage/index.php?action=close">Cancel</a>
        </div>
    </form>
</div>


</body>
<footer>
     
</footer>

</html>