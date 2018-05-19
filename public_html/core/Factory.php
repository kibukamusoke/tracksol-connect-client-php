<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 9:43 PM
 */

include_once ('Connect.php');
include_once ('NWSC.php');
include_once ('Picklist.php');

class Factory
{

    public static function Connect($CLASSES) {
        return new Connect($CLASSES);
    }

    public static function NWSC($CLASSES) {
        return new NWSC($CLASSES);
    }

    public static function Picklist($CLASSES) {
        return new Picklist($CLASSES, self::Connect($CLASSES));
    }



}