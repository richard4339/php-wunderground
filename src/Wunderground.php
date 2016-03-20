<?php

/**
 * php-wunderground
 * @author Richard Lynskey <richard@mozor.net>
 * @copyright Copyright (c) 2012, Richard Lynskey
 * @license http://www.gnu.org/licenses/ GPLv3
 * @version 0.1
 *
 * Built 2016-03-20 09:59 CDT by richard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

class Wunderground
{

    public $apikey;

    function __construct($key)
    {
        $this->apikey = urlencode($key);
    }

    function currentConditions($location) {
        if (empty($location))
            return array();

        $location = urlencode($location);

        $json = file_get_contents('http://api.wunderground.com/api/'.$this->apikey.'/conditions/q/'.$location.'.json');
        return json_decode($json);
    }


}