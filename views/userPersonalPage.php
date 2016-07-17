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

        <?php if (empty($_POST['newUserProduct'])) { ?>
        <form action="../myPage/" method="post">
            <th>
                <input type="hidden" name="newUserProduct" value="true">
                <input type="submit" value="Add Product">
            </th>
        </form>
        <?php } ?>

        <?php if (!empty($_POST['newUserProduct'])) { ?>
            <form enctype="multipart/form-data" class="product-add-form" method="post" action="../myPage/">
                <input name="productPicture" type="file" >
                <input type="text" name="productName" placeholder="Product Name" autofocus required>
                 <input type="text" name="productCategories" placeholder="Product Categories" required>
                <input type="hidden" name="newUserProductSubmitted" value="true">
                 <input type="submit" value="Add">
            </form>
        <?php } ?>

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
                    <th>IMG</th>
                    <th><?= $uP->product_name ?></th>
                    <th><?php if (!is_null($uP->category_name1)){ ?><?= $uP->category_name1 ?><?php }
                        if (!is_null($uP->category_name2)){ ?>, <?= $uP->category_name2 ?> <?php }
                        if (!is_null($uP->category_name3)){ ?>, <?= $uP->category_name3 ?>  <?php }
                        if (!is_null($uP->category_name3)){ ?>, <?= $uP->category_name4 ?>  <?php }
                        if (!is_null($uP->category_name3)){ ?>, <?= $uP->category_name5 ?>  <?php } ?></th>
                    <th><?= $uP->from_date ?></th>
                    <form action="../myPage/" method="post">
                        <th>
                            <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                            <input type="hidden" name="from_date" value="<?= $uP->from_date ?>">
                            <input type="hidden" name="editUserProduct" value="true">
                            <input type="submit" value="Edit">
                    </form>
                    </th>
                    <form action="../myPage/" method="post">
                        <th>
                            <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                            <input type="hidden" name="from_date" value="<?= $uP->from_date ?>">
                            <input type="hidden" name="deleteUserProduct" value="true">
                            <input type="submit" value="Delete">
                        </th>
                    </form>
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