<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="main">
<div class="wrap">
    <div class="header">
    </div>
    <div align="center">
        <h1>My Product List Page | <?php echo $userName; ?>
            <a href='../login/index.php?action=logout' class="log-out">(logout)</a>
        </h1><br>
        <?php if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) { ?>
            <div align="center">
                <form class="vertical-form-bottom">
                    <input id="error" type="hidden" readonly><label for="error">Image you submitted is incorrect. Only
                        png, jpg and gif files not larger than 3 MB can be used.<br>
                        <a href='../myPage/index.php?action=close' class="close">(close)</a></label>
                </form>
            </div>
        <?php } ?>

        <form enctype="multipart/form-data" class="product-add-form" method="post" action="../myPage/">
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input name="productPicture" type="file">
            <input type="text" name="productName" placeholder="Product Name" autofocus required maxlength="254">
            <input type="text" style="width: 177px" width="300" name="productCategoriesString"
                   placeholder="Categories (use , ; delimiters)"
                   maxlength="1000">
            <input type="hidden" name="newUserProductSubmitted" value="true">
            <input type="submit" value="Add">
        </form>


        <?php if (!is_null($userProducts[0])) { ?>

        <table class="products-table">


            <?php foreach ($userProducts as $uP): ?>

                <tr>
                    <!-- if Edit button has been clicked we need to open edit-form for that specific product line-->
                    <?php if (isset($editUserProductFlag) && isset($productId) && $productId == $uP->product_id) {
                        include "editProduct.php"; ?>




                        <!-- if Edit button was NOT clicked we need to open regular product list-->
                    <?php } else { ?>

                        <th width="50"><?php if (is_null($uP->product_img_name)) { ?><img
                                src="../media/products/shopping-cart-32.png"><?php } else { ?><a
                                href="../uploads/<?= $userId ?>/original/<?= $uP->product_img_name ?>" target="_blank">
                                <img
                                    src="../uploads/<?= $userId ?>/cropped/<?= $uP->product_img_name ?>"></a><?php } ?>
                        </th>
                        <th width="200"><?= htmlspecialchars(ltrim(rtrim($uP->product_name))) ?></th>
                        <th width="300"><?php
                            $lastCategoryElement = end($productCategories[$uP->product_id]);
                            foreach ($productCategories[$uP->product_id] as $pC):
                                if (!is_null($pC)) {

                                    if (!is_null($pC->category_name) && $pC->category_name != "") {
                                        echo escapeSpecialCharactersHTML($pC->category_name);
                                        if ($lastCategoryElement->category_id != $pC->category_id && count($productCategories[$uP->product_id]) > 2)
                                            echo ", ";
                                    }
                                }
                            endforeach; ?>
                        </th>

                        <th width="66" align="right">
                            <a href='../myPage/index.php?action=edit&product_id=<?= $uP->product_id ?>'><img src="../media/products/edit-32.png"  HEIGHT="24" WIDTH="24"></a>
                        </th>

                        <th width="32" align="right">
                            <form action="../myPage/" method="post">
                                <input type="hidden" name="line_id" value="">
                                <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                                <input type="hidden" name="from_date" value="">
                                <input type="hidden" name="deleteUserProduct" value="true">
                                <input TYPE="image" SRC="../media/products/garbage-32.png" HEIGHT="24" WIDTH="24"
                                       BORDER="0" ALT="Delete" onclick="if(confirm('Delete product?'))submit();else return false;">

                            </form>
                        </th>
                    <?php } ?>
                </tr>
            <?php endforeach;
            } else { ?>
                <br>You do not have products in your cart yet.
            <?php }
            ?>
        </table>
    </div>
</div>
<footer>
    Personal Info / 2016
</footer>
</body>

</html>