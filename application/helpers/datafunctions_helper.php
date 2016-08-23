<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function escapeSpecialCharactersHTML($string)
{
    return html_escape(ltrim(rtrim($string)));
}
