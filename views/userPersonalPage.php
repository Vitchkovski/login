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
        <form class="vertical-form-info" action="../login/" method="post">
            <h1>Personal Info</h1><br>
            <p align=left><input id="info" type="hidden" readonly><label for="info">ID: <?= $userId ?><br></label>
                Username: <?= $userName ?><br>
                Email: <?= $userEmail ?></p>
            <input type="hidden" name="logout_flag" value="true">
            <input type="submit" value="Log Out"><br>
        </form>


        <form enctype="multipart/form-data" class="product-add-form" method="post" action="../myPage/">
            <input name="productPicture" type="file">
            <input type="text" name="productName" placeholder="Product Name" autofocus required maxlength="254">
            <input type="text" name="productCategoriesString" placeholder="Product Categories" required
                   maxlength="1000">
            <input type="hidden" name="newUserProductSubmitted" value="true">
            <input type="submit" value="Add">
        </form>


        <?php if (!is_null($userProducts[0])) { ?>

        <table class="products-table">
            <tr>
                <th>IMG</th>
                <th>Product</th>
                <th>Categories</th>
                <th>Date Added</th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach ($userProducts as $uP): ?>

                <tr>
                    <!-- if Edit button has been clicked we need to open edit-form for that specific product line-->
                    <?php if (!empty($_POST['editUserProductFlag']) && $_POST['line_id'] == $uP->line_id) { ?>
                        <form enctype="multipart/form-data" class="product-add-form" method="post" action="../myPage/">
                            <th><input name="productPicture" type="file"></th>
                            <th><input type="text" name="productName" value="<?= $uP->product_name ?>"
                                       placeholder="Product Name" autofocus required maxlength="254"></th>
                            <th><input type="text" name="productCategoriesString"
                                       value="<?php if (!is_null($uP->category_name1)) { ?><?= $uP->category_name1 ?><?php }
                                       if (!is_null($uP->category_name2)) { ?>, <?= $uP->category_name2 ?><?php }
                                       if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name3 ?><?php }
                                       if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name4 ?><?php }
                                       if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name5 ?><?php } ?>"
                                       placeholder="Product Categories"
                                       required maxlength="1000">
                                <input type="hidden" name="updateUserProductString" value="true"></th>

                            <th></th>
                            <th><input type="submit" value="Save"></th>
                        </form>
                        <th>
                            <form action="../myPage/" method="post">
                                <input type="hidden" name="cancelEditModeFlag" value="true">
                                <input type="submit" value="Cancel">
                            </form>
                        </th>

                    <?php } else { ?>

                        <th>IMG</th>
                        <th><?= $uP->product_name ?></th>
                        <th><?php if (!is_null($uP->category_name1)) { ?><?= $uP->category_name1 ?><?php }
                            if (!is_null($uP->category_name2)) { ?>, <?= $uP->category_name2 ?><?php }
                            if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name3 ?><?php }
                            if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name4 ?><?php }
                            if (!is_null($uP->category_name3)) { ?>, <?= $uP->category_name5 ?><?php } ?></th>
                        <th><?= $uP->from_date ?></th>

                        <th>
                            <form action="../myPage/" method="post">
                                <input type="hidden" name="line_id" value="<?= $uP->line_id ?>">
                                <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                                <input type="hidden" name="from_date" value="<?= $uP->from_date ?>">
                                <input type="hidden" name="editUserProductFlag" value="true">
                                <input type="submit" value="Edit">
                            </form>
                        </th>

                        <th>
                            <form action="../myPage/" method="post">
                                <input type="hidden" name="line_id" value="<?= $uP->line_id ?>">
                                <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                                <input type="hidden" name="from_date" value="<?= $uP->from_date ?>">
                                <input type="hidden" name="deleteUserProduct" value="true">
                                <input type="submit" value="Delete">
                            </form>
                        </th>

                    <?php } ?>
                </tr>


            <?php endforeach;
            } else { ?>
                <br>You do not have products in your cart yet.
            <?php } ?>
        </table>

    </div>


</div>
<footer>
    Personal Info / 2016
</footer>
</body>

</html>