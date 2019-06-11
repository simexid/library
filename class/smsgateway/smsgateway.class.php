<?php
/*
Simexid SMS Gateway class

Created: 11/01/2019

Revision: 0.0.1

Author: Pietro Saccone <pedro.s@simexid.org>

Require: PHP >5.6, curl

Copyright (C) 2019  Simexid  Informatica di Saccone Pietro

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program, under LICENSE.txt file.  If not, see <https://www.gnu.org/licenses/>.
    
 */

class smsGateway
{

    public $apiKey = ''; //set your api key here or set later by calling setApiKey() method
    public $deviceId = ''; //you can set default deviceId ore provide later by calling setDevice() method

    public $message = '';
    public $number = '';

    public $host = 'https://api.simexid.org/notificator/';
    public $service = array('sendSms' => 'pushSmsGw/', 'getDevice' => 'deviceList/');

    /*
        setApiKey(): method used to set the apiKey/token for request
    */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    /*
        setDevice($device): method used to set the device for sending SMS
    */
    public function setDevice($device)
    {
        $this->deviceId = $device;
    }

    /*
        setNumber($num): method used to set the number of SMS
    */
    public function setNumber($num)
    {
        $this->number = $num;
    }

    /*
        setMessage($mess): method used to set the message of SMS
    */
    public function setMessage($mess)
    {
        $this->message = $mess;
    }

    /*
        getDevice(): method used to get registered device and their ID
        You must set first apiKey
    */
    public function getDevice()
    {
        header('Content-type:application/json');
        $chr = curl_init($this->host . $this->service['getDevice']);

        $data = '{}';

        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'X-SMX-TOKEN ' . $this->apiKey,
        );

        curl_setopt($chr, CURLOPT_HEADER, false);
        curl_setopt($chr, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chr, CURLOPT_POST, true);
        curl_setopt($chr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chr, CURLOPT_POSTFIELDS, $data);
        curl_setopt($chr, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($chr);

        if (empty($result)) {

            $resArray = array(
                'error' => 'NO RESPONSE', 'exitCode' => 'KO',
            );
            $result = json_encode($resArray);
        } 
        curl_close($chr);

        return $result;
    }

    /*
        sendSms(): method used to send SMS
        You must set first apiKey, deviceId, number and message
    */
    public function sendSms()
    {
        header('Content-type:application/json');
        $chr = curl_init($this->host . $this->service['sendSms']);

        $numero = $this->number;
        $messaggio = $this->message;
        $device = $this->deviceId;

        $data = '{
                    "numero": "' . $numero . '",
                    "messaggio": "' . $messaggio . '",
                    "device": "' . $device . '",

                  }';

        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'X-SMX-TOKEN ' . $this->apiKey,
        );

        curl_setopt($chr, CURLOPT_HEADER, false);
        curl_setopt($chr, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($chr, CURLOPT_POST, true);
        curl_setopt($chr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chr, CURLOPT_POSTFIELDS, $data);
        curl_setopt($chr, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($chr);

        if (empty($result)) {

            $resArray = array(
                'error' => 'NO RESPONSE', 'exitCode' => 'KO',
            );
            $result = json_encode($resArray);
        }
        curl_close($chr);

        return $result;
    }

}
