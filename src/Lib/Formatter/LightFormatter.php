<?php
/**
 * @link https://github.com/date-fns/date-fns/blob/master/src/_lib/format/lightFormatters/index.js
 */
namespace A2htray\DateFNS\Lib\Formatter;

use A2htray\DateFNS\Lib\Helper;
use DateTime;

class LightFormatter {
    /**
     * 年
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function y(DateTime $dateTime, $token) {
        // | Year     |     y | yy |   yyy |  yyyy | yyyyy |
        // |----------|-------|----|-------|-------|-------|
        // | AD 1     |     1 | 01 |   001 |  0001 | 00001 |
        // | AD 12    |    12 | 12 |   012 |  0012 | 00012 |
        // | AD 123   |   123 | 23 |   123 |  0123 | 00123 |
        // | AD 1234  |  1234 | 34 |  1234 |  1234 | 01234 |
        // | AD 12345 | 12345 | 45 | 12345 | 12345 | 12345 |
        $signedYear = (int)$dateTime->format('Y');
        $year = $signedYear > 0 ? $signedYear : 1 - $signedYear;

        return Helper::addLeadingZeros(($token == 'yy' ? $year % 100 : $year), strlen($token));
    }

    /**
     * 月
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function _M(DateTime $dateTime, $token) {
        $month = (int)$dateTime->format('m');
        return Helper::addLeadingZeros($month + 1, strlen($token));
    }

    /**
     * 每月几日
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function d(DateTime $dateTime, $token) {
        $day = (int)$dateTime->format('d');
        return Helper::addLeadingZeros($day, strlen($token));
    }

    /**
     * AM 或者 PM
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function a(DateTime $dateTime, $token) {
        $hour = (int)$dateTime->format('H');
        $dayPeriodEnumValue = $hour / 12 >= 1 ? 'pm' : 'am';
        switch ($token) {
            case 'a':
            case 'aa':
            case 'aaa':
                return strtoupper($dayPeriodEnumValue);
            case 'aaaa':
                return $dayPeriodEnumValue[0];
            default:
                return $dayPeriodEnumValue == 'am' ? 'a.m.' : 'p.m.';
        }
    }

    /**
     * 1-12
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function h(DateTime $dateTime, $token) {
        $hour = (int)$dateTime->format('H') % 12;
        if ($hour == 0) {
            $hour = 12;
        }

        return Helper::addLeadingZeros($hour, strlen($token));
    }

    /**
     * 0-23
     * @param DateTime $dateTime
     * @param $token
     * @return string
     */
    public static function _H(DateTime $dateTime, $token) {
        $hour = (int)$dateTime->format('H');
        return Helper::addLeadingZeros($hour, strlen($token));
    }

    public static function m(DateTime $dateTime, $token) {
        $minute = (int)$dateTime->format('i');
        return Helper::addLeadingZeros($minute, strlen($token));
    }

    public static function s(DateTime $dateTime, $token) {
        $second = (int)$dateTime->format('s');
        return Helper::addLeadingZeros($second, strlen($token));
    }

    public static function _S(DateTime $dateTime, $token) {
        $ts = $dateTime->getTimestamp();
        $fractionTs = floor($ts * pow(10, strlen($token) - 3));
        return Helper::addLeadingZeros($fractionTs, strlen($token));
    }
}