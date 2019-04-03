<?php

namespace A2htray\DateFNS\Lib;

use A2htray\DateFNS\Abs\CallAdapter;
use \DateTime;
use A2htray\DateFNS\Utils;

class Helper {
    /**
     * 给数值加入前导 0
     * @param int $number
     * @param int $targetLength
     * @return string
     *
     * @link https://github.com/date-fns/date-fns/blob/master/src/_lib/addLeadingZeros/index.js
     */
    public static function addLeadingZeros(int $number, int $targetLength) : string {
        $sign = $number >= 0 ? '' : '-';
        /**
         * str_pad — Pad a string to a certain length with another string
         * @link https://www.php.net/manual/en/function.str-pad.php
         */
        $output = str_pad(abs($number), $targetLength, '0', STR_PAD_LEFT);
        return $sign . $output;
    }

    /**
     * @param object $target
     * @param object $dirtyObject
     * @return object
     * @throws \TypeError
     *
     * @link https://github.com/date-fns/date-fns/blob/master/src/_lib/assign/index.js
     */
    public static function assign(object $target, object $dirtyObject) : object {
        if ($target == null) {
            throw new \TypeError('assign requires that input parameter not be null or undefined');
        }
        // 如果 $dirtyObject 为 null, 则赋值为标准类对象
        $dirtyObject = clone ($dirtyObject ?? new \stdClass());

        return new CallAdapter($target, $dirtyObject);
    }

    /**
     * @param object $dirtyObject
     * @return object
     *
     * @link https://github.com/date-fns/date-fns/blob/master/src/_lib/cloneObject/index.js
     */
    public static function cloneObject(object $dirtyObject) {
        return clone $dirtyObject;
    }

    /**
     * 与格林威治时间的差值(毫秒)，将秒数也考虑进入
     * @param DateTime $dateTime
     * @return int
     */
    public static function getTimezoneOffsetInMilliseconds(DateTime $dateTime) : int {
        return $dateTime->getOffset() * 1000 + (int)$dateTime->format('s') * Utils::MILLISECONDS_IN_SECONDS;
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public static function getUTCDayOfYear(DateTime $dateTime) {
        $ts = $dateTime->getTimestamp();
        $dt = new DateTime($dateTime->format('Y') . '-01-01');
        $diff = $ts - $dt->getTimestamp();
        return (int)floor($diff / Utils::SECONDS_IN_DAY) + 1;
    }

    public static function startOfUTCISOWeek(DateTime $dateTime) : DateTime {
        $dayInWeek = (int)$dateTime->format('w');
        $dt = Utils::subDays($dateTime, $dayInWeek == 0 ? 6 : ($dayInWeek - 1));
        return new DateTime($dt->format('Y-M-d'));
    }
}