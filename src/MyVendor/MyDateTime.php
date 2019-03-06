<?php
/**
 * Created by PhpStorm.
 * User: Esteban
 * Date: 05/03/2019
 * Time: 14:17
 */

namespace App\MyVendor;


use DateTimeZone;

class MyDateTime extends \DateTime
{

    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
    }

    public function __toString()
    {
        return $this->format("Y-m-d H:i:s");
    }
}