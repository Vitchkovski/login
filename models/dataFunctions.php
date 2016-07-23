<?php

function validateIfPasswordSecure($password)
{
    if (strlen($password) < 6)
        return false;

    return true;
}

function explodeWithMultipleDelimiters($delimiters, $string)
{

    $multipleDelimitersIntoOne = str_replace($delimiters, $delimiters[0], $string);
    $explodeResult = explode($delimiters[0], $multipleDelimitersIntoOne);
    return $explodeResult;
}

function clearArray($array)
{
    return array();
}

function categoryStringToArray($escapedProductCategoriesString)
{

    $categoryDelimitersList = array(",", ";", ", ", "; ");


    $productCategoriesArray = explodeWithMultipleDelimiters($categoryDelimitersList, $escapedProductCategoriesString);
    for ($i = 0; $i <= 4; $i++) {
        if (isset($productCategoriesArray[$i])) {
            $limitedCategoriesArray[$i] = $productCategoriesArray[$i];
        } else {
            $limitedCategoriesArray[$i] = null;
        }

    }

    return $limitedCategoriesArray;


}

function uploadProductPicture ($userId){


    $typesAllowed = array('image/gif', 'image/png', 'image/jpeg', 'image/bmp');

    if (in_array($_FILES['productPicture']['type'], $typesAllowed)) {

        if ($_FILES["productPicture"]["size"] < 1024 * 3 * 1024) {


            if (is_uploaded_file($_FILES["productPicture"]["tmp_name"])) {


                @mkdir("../uploads/" . $userId."/cropped", 0777);
                @mkdir("../uploads/" . $userId."/original", 0777);

                $pictureNameAfterUpload = $userId."-".time().".png";

                move_uploaded_file($_FILES["productPicture"]["tmp_name"], "../uploads/" . $userId . "/"."original/" .$pictureNameAfterUpload);


                $productImage = new resizeImage();
                $productImage->load("../uploads/" . $userId . "/"."original/" .$pictureNameAfterUpload);

                $productPictureHeight = $productImage->getHeight();
                $productPictureWidth = $productImage->getWidth();

                if ($productPictureHeight>=$productPictureWidth)
                    $productImage->resizeToHeight(48);
                else
                    $productImage->resizeToWidth(48);


                $productImage->save("../uploads/" . $userId . "/"."cropped/" .$pictureNameAfterUpload);

                return $pictureNameAfterUpload;


            } else {
                return "Error on load";
            }
        } else {
            return "File size is too big";
        }
    } else {
        return "Type is not allowed";
    }

}

class resizeImage
{

    var $image;
    var $image_type;

    function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
    {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }


    function getWidth()
    {
        return imagesx($this->image);
    }

    function getHeight()
    {
        return imagesy($this->image);
    }

    function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }


    function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}

function escapeSpecialCharactersHTML($string){
    return htmlspecialchars(ltrim(rtrim($string)));
}



?>