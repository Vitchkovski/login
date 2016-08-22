<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>

    <script src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
    <script type="text/javascript">

        function addField() {
            var telnum = parseInt($("#add_field_area").find("div.form-group:last").attr("id").slice(8))+1;
            $('div#add_field_area').append('<div id="category'+telnum+'" class="form-group"><input type="text" class = "form-control" name="productCategoriesArray[]" placeholder = "Enter product category" maxlength = "1000" value=""/></div>');

        }

    </script>

    <link href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/css/signin.css"); ?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>