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
                            <input type="hidden" name="edit_flag" value="true">
                            <input type="submit" value="Edit">
                    </form>
                    </th>
                    <form action="../myPage/" method="post">
                        <th>
                            <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                            <input type="hidden" name="from_date" value="<?= $uP->from_date ?>">
                            <input type="hidden" name="delete_flag" value="true">
                            <input type="submit" value="Delete">
                        </th>
                    </form>
                </tr>


            <?php endforeach;
            } else { ?>
                You do not have products in your cart yet.
            <?php } ?>
        </table>

    </div>


</div>
<footer>
    Personal Info / 2016
</footer>
</body>

</html>