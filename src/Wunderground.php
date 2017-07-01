<?php

/**
 * php-wunderground
 * @author Richard Lynskey <richard@mozor.net>
 * @copyright Copyright (c) 2016-2017, Richard Lynskey
 * @license http://www.gnu.org/licenses/ GPLv3
 * @version 0.0.6
 *
 * Built 2017-07-01 14:49 CDT by richard
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
 */

namespace Wunderground\Objects;

use InvalidArgumentException;

/**
 * Class Wunderground
 * @package Wunderground\Objects
 */
class Wunderground
{

    /**
     * @var string Weather Underground provided API key
     */
    public $apiKey;

    /**
     * @var string URL for the API calls
     */
    private $apiURL = 'https://api.wunderground.com/api/';

    /**
     * @var string File path for testing purposes
     */
    private $apiFilePath = '';

    /**
     * Wunderground constructor.
     * @param string $key API Key
     * @param string $apiURL (Optional) URL for API calls
     * @param string $filePath (Optional) file path to use for API calls, for testing purposes only
     * @throws InvalidArgumentException if an empty API key is provided, or a file path is provided but is an invalid file
     */
    function __construct($key, $apiURL = '', $filePath = '')
    {
        if (empty($key)) {
            throw new InvalidArgumentException('Wunderground class requires a valid API key');
        }
        $this->apiKey = urlencode($key);

        if (!empty($apiURL)) {
            $this->apiURL = $apiURL;
        }

        if (!empty($filePath)) {
            if (!file_exists($filePath)) {
                throw new InvalidArgumentException('File path does not exist');
            }
            $this->apiFilePath = $filePath;
        }


    }

    /**
     * Returns the current conditions as a decoded JSON object
     *
     * @param string|int $arg1 State or zip code
     * @param string $arg2 City if first argument is a state
     * @return mixed
     */
    function currentConditions($arg1, $arg2 = '')
    {
        $location = $this->buildLocation($arg1, $arg2);

        $results = $this->apiCall(Methods::CONDITIONS, $location);

        return $results->current_observation;
    }

    /**
     * Returns current severe weather alerts as a decoded JSON object
     *
     * @param string|int $arg1 State or zip code
     * @param string $arg2 City if first argument is a state
     * @return mixed
     */
    function alerts($arg1, $arg2 = '')
    {
        $location = $this->buildLocation($arg1, $arg2);

        $results = $this->apiCall(Methods::ALERTS, $location);

        return $results->alerts;
    }

    /**
     * Returns current conditions and severe weather alerts as a decoded JSON object
     *
     * @param string|int $arg1 State or zip code
     * @param string $arg2 City if first argument is a state
     * @return mixed
     */
    function currentConditionsAndAlerts($arg1, $arg2 = '')
    {
        $location = $this->buildLocation($arg1, $arg2);

        $results = $this->apiCall(Methods::CONDITIONS . '/' . Methods::ALERTS, $location);

        $return = new \stdClass();
        $return->conditions = $results->current_observation;
        $return->alerts = $results->alerts;

        return $return;
    }

    /**
     * URL encodes the arguments to pass to the API call
     *
     * @param string|int $arg1 State or zip code
     * @param string $arg2 City if first argument is a state
     * @return string
     */
    private function buildLocation($arg1, $arg2)
    {

        if (empty($arg1)) {
            return $this->apiCall(Methods::CONDITIONS, 'CA/San_Francisco');
        }

        if (empty($arg2)) {
            $location = urlencode($arg1);
        } else {
            $location = urlencode($arg1) . '/' . urlencode($arg2);
        }

        return $location;
    }

    /**
     * Makes the API call
     *
     * @param Methods|string $method API method
     * @param string $location url encoded location
     * @return mixed
     */
    private function apiCall($method, $location)
    {
        if (!empty($this->apiFilePath)) {
            $url = $this->apiFilePath;
        } else {
            $url = $this->apiURL . $this->apiKey . '/' . $method . '/q/' . $location . '.json';
        }
        $json = file_get_contents($url);
        return json_decode($json);
    }


}