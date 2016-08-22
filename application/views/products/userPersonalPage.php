<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

    <h2 align="center">My Product List Page | <?php echo $this->session->userdata('userSessionName'); ?>

    </h2><br>
    <?php if (isset($_SESSION['imageIncorrectFlag']) && $_SESSION['imageIncorrectFlag'] == true) { ?>
        <div align="center">

            <div class="alert alert-danger">
                <a href="<?php echo base_url("index.php/products/closeMessage"); ?>" class="close" data-dismiss="alert"
                   aria-label="close">&times;</a>
                Image you submitted is incorrect. Only
                png, jpg and gif files not larger than 3 MB can be used.<br>
            </div>

        </div>
    <?php } ?>
    <div align="right">
        <a class="btn btn-success" href='<?php echo base_url("index.php/products/addProduct"); ?>'>Add New Product</a>
        <a href='<?php echo base_url("index.php/users/logout"); ?>' class="btn btn-warning">Logout</a>
    </div>
    <?php
    if (!empty($userProducts)) { ?>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Picture</th>
            <th>Product Name</th>
            <th>Product Category</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($userProducts as $uP): ?>
            <tr>
                <th><?php if (!is_null($uP->product_img_name)) { ?><a
                        href="<?php echo base_url("assets/img/uploads/"); ?><?= $userId ?>/original/<?= $uP->product_img_name ?>" target="_blank">
                        <img class="img-rounded"
                             src="<?php echo base_url("assets/img/uploads/"); ?><?= $userId ?>/cropped/<?= $uP->product_img_name ?>"></a><?php } ?>
                </th>
                <th style="word-wrap: break-word;min-width: 160px;max-width: 300px;"><?= htmlspecialchars(ltrim(rtrim($uP->product_name))) ?></th>
                <th style="word-wrap: break-word;min-width: 160px;max-width: 300px;"><?php
                    $lastCategoryElement = end($productCategories[$uP->product_id]);
                    //var_dump($productCategories[$uP->product_id]);
                    foreach ($productCategories[$uP->product_id] as $pC):

                        if (!is_null($pC)) {

                            if (!is_null($pC->category_name) && $pC->category_name != "") {
                                echo escapeSpecialCharactersHTML($pC->category_name);
                                if ($lastCategoryElement->category_id != $pC->category_id
                                    && count($productCategories[$uP->product_id]) > 1
                                )
                                    echo ", ";
                            }
                        }
                    endforeach; ?>
                </th>

                <th>
                    <div align="right">
                        <form action="<?php echo base_url("index.php/products/deleteProduct"); ?>" method="post">
                            <a class="btn btn-default"
                               href='<?php echo base_url("index.php/products/editProduct/"); ?><?= $uP->product_id ?>'>
                                Edit
                            </a>
                            <input type="hidden" name="product_id" value="<?= $uP->product_id ?>">
                            <input type="hidden" name="deleteUserProduct" value="true">
                            <input class="btn btn-danger" TYPE="submit"
                                   onclick="if(confirm('Delete product?'))submit();else return false;" value="Delete">

                        </form>
                    </div>
                </th>

            </tr>
        <?php endforeach;
        } else { ?>
            <div align="center">
                <br>You do not have products in your cart yet.
            </div>
        <?php }
        ?>
        </tbody>
    </table>
    <?php if (isset($errorMsg)) {
        echo '<p class="alert alert-danger" >' . $errorMsg . '</p >';
    }
    echo validation_errors('<p class="alert alert-danger">'); ?>
</div>
