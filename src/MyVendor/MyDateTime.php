<?php
/**
 * Created by PhpStorm.
 * User: Esteban
 * Date: 05/03/2019
 * Time: 14:17
 */

namespace App\MyVendor;


class MyDateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format("Y-m-d H:i:s");
    }
}