<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function escapeSpecialCharactersHTML($string)
{
    return html_escape(ltrim(rtrim($string)));
}

function uploadProductPicture($userId)
{


    $typesAllowed = array('image/gif', 'image/png', 'image/jpeg', 'image/bmp');

    if (in_array($_FILES['productPicture']['type'], $typesAllowed)) {

        if ($_FILES["productPicture"]["size"] < 1024 * 3 * 1024) {


            if (is_uploaded_file($_FILES["productPicture"]["tmp_name"])) {


                @mkdir("/var/www/vitchkovski.com/public_html/codeigniter/assets/img/uploads/" . $userId . "/cropped", 0777, true);
                @mkdir("/var/www/vitchkovski.com/public_html/codeigniter/assets/img/uploads/" . $userId . "/original", 0777, true);

                $pictureNameAfterUpload = $userId . "-" . time() . ".png";

                move_uploaded_file($_FILES["productPicture"]["tmp_name"], "/var/www/vitchkovski.com/public_html/codeigniter/assets/img/uploads/" . $userId . "/original/" . $pictureNameAfterUpload);


                $productImage = new resizeImage();
                $productImage->load_img("/var/www/vitchkovski.com/public_html/codeigniter/assets/img/uploads/" . $userId . "/" . "original/" . $pictureNameAfterUpload);

                $productPictureHeight = $productImage->getHeight();
                $productPictureWidth = $productImage->getWidth();

                if ($productPictureHeight >= $productPictureWidth)
                    $productImage->resizeToHeight(48);
                else
                    $productImage->resizeToWidth(48);


                $productImage->save("/var/www/vitchkovski.com/public_html/codeigniter/assets/img/uploads/" . $userId . "/" . "cropped/" . $pictureNameAfterUpload);

                return $pictureNameAfterUpload;


            } else {
                return "Error on load";
            }
        } else {
            return "Error on load";
        }
    } else {
        return "Error on load";
    }

}

class resizeImage
{

    var $image;
    var $image_type;

    function load_img($filename)
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
