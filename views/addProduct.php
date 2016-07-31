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
    <h2> Add New Product to the List | <?php echo $userName; ?><a href='../login/index.php?action=logout'> (logout)</a>
    </h2>


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
            <label for="productPicture">Product Picture</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input name="productPicture" type="file">
        </div>
        <div class="form-group">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" class="form-control" placeholder="Enter product name" autofocus
                   maxlength="254" value="<?=$productName?>">
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
        <input class="btn btn-default" name="addSaveCategory" type="submit" value="Add Category">
        <input class="btn btn-success" name="saveProduct" type="submit" value="Save">
    </form>
</div>


</body>
<footer>
     
</footer>

</html>