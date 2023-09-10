<?php

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

if (!function_exists('my_mb_ucfirst')) {
    function my_mb_ucfirst($str)
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));

        return $fc . mb_substr($str, 1);
    }
}

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

if (!function_exists('strrevpos')) {
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) {
            return false;
        } else {
            return strlen($instr) - $rev_pos - strlen($needle);
        }
    }
}

if (!function_exists('after')) {
    function after($thiss, $inthat)
    {
        if (!is_bool(strpos($inthat, $thiss))) {
            return substr($inthat, strpos($inthat, $thiss) + strlen($thiss));
        }
    }
}

if (!function_exists('after_last')) {
    function after_last($thiss, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $thiss))) {
            return substr($inthat, strrevpos($inthat, $thiss) + strlen($thiss));
        }
    }
}

if (!function_exists('before')) {
    function before($thiss, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $thiss));
    }
}

if (!function_exists('before_last')) {
    function before_last($thiss, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $thiss));
    }
}

if (!function_exists('between')) {
    function between($thiss, $that, $inthat)
    {
        return before($that, after($thiss, $inthat));
    }
}

if (!function_exists('between_last')) {
    function between_last($thiss, $that, $inthat)
    {
        return after_last($thiss, before_last($that, $inthat));
    }
}

function sendSMS($sms)
{

    // Set your 46elks API username and API password here
    // You can find them at https://dashboard.46elks.com/
    $username = 'uf4ce7d065acbd94c7e12ba65cc0d1567';
    $password = '8152CC9B3029B5D37E1C99F6B4D2EFE7';

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Authorization: Basic " .
                base64_encode($username . ':' . $password) . "\r\n" .
                "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($sms),
            'timeout' => 10
        )));

    $response = file_get_contents(
        'https://api.46elks.com/a1/SMS', false, $context);

    if (!strstr($http_response_header[0], "200 OK"))
        return $http_response_header[0];

    return $response;
}

function check_odd($number)
{
    if ($number == 0)
        return 1;
    else if ($number == 1)
        return 0;
    else if ($number < 0)
        return check_odd(-$number);
    else
        return check_odd($number - 2);
}

function check_number($number)
{

    if (str_starts_with($number, '07')) {
        $number = substr($number, 1);
    } elseif (str_starts_with($number, '46')) {
        $number = substr($number, 2);
    }

    return '+46' . $number;
}

function abs_diff($v1, $v2)
{
    $diff = $v1 - $v2;
    return $diff < 0 ? (-1) * $diff : $diff;
}

function roundUpToAny($n, $x = 5)
{
    return (ceil($n) % $x === 0) ? ceil($n) : round(($n + $x / 2) / $x) * $x;
}

function roundToNearestMinuteInterval(\DateTime $dateTime, $minuteInterval = 15)
{
    return $dateTime->setTime(
        $dateTime->format('H'),
        round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
        0
    );
}

function formatPhoneNumber($phoneNumber)
{
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    if (strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+' . $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
    } else if (strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = $areaCode . ' - ' . $nextThree . ' ' . $lastFour;
    } else if (strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree . '-' . $lastFour;
    }
    return $phoneNumber;
}
