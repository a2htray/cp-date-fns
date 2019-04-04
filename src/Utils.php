<?php

namespace A2htray\DateFNS;

use DateInterval;
use DateTime;

class Utils
{
    const MILLISECONDS_IN_DAY = 86400000;
    const MILLISECONDS_IN_SECONDS = 1000;
    const SECONDS_IN_DAY = 86400;
    const SECONDS_IN_HOUR = 3600;
    const MINUTES_IN_DAY = 1440;
    const HOURS_IN_DAY = 24;
    const MILLISECONDS_IN_MINUTE = 600000;

    const UNIX_STAMP = 999999;

    public static function addYears(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('P' . $dirtyAmount . 'Y'));
        return $clone;
    }

    public static function addQuarters(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $ret = self::addMonths($dateTime, $dirtyAmount * 3);
        return $ret;
    }

    public static function addMonths(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('P' . $dirtyAmount . 'M'));
        return $clone;
    }

    public static function addWeeks(DateTime $dateTime, int $dirtyAmount) : DateTime {
        return self::addDays($dateTime, $dirtyAmount * 7);
    }

    public static function addDays(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('P' . $dirtyAmount . 'D'));
        return $clone;
    }

    public static function addHours(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('PT' . $dirtyAmount . 'H'));
        return $clone;
    }

    public static function addMinutes(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('PT' . $dirtyAmount . 'M'));
        return $clone;
    }

    public static function addSeconds(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $clone->add(new DateInterval('PT' . $dirtyAmount . 'S'));
        return $clone;
    }

    public static function addMilliseconds(DateTime $dateTime, int $dirtyAmount) : DateTime {
        // TODO need to modify, it is error
        $base = (int)$dateTime->format('u');
        $seconds = (int)(($base + $dirtyAmount) / self::UNIX_STAMP);
        $mod = ($base + $dirtyAmount) % self::UNIX_STAMP - 1;
        $dt = self::addSeconds($dateTime, $seconds);
        $dt->setTime((int)$dt->format('H'), (int)$dt->format('i'), (int)$dt->format('s'), $mod);
        return $dt;
    }

    public static function closeIndexTo(DateTime $dateTime, array $dirtyDateArray) : int {
        $len = count($dirtyDateArray);
        $base = self::getMilliseconds($dateTime);
        $index = 0;
        $minDistance = abs($base - self::getMilliseconds($dirtyDateArray[0]));

        for ($i = 1; $i < $len; $i++) {
            $distance = abs($base - self::getMilliseconds($dirtyDateArray[$i]));
            if ($distance < $minDistance) {
                $index = $i;
                $minDistance = $distance;
            }
        }

        return $index;
    }

    public static function compareAsc(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $offset = self::getMilliseconds($dateTimeLeft) - self::getMilliseconds($dateTimeRight);
        if ($offset > 0) {
            return 1;
        } elseif ($offset < 0) {
            return -1;
        } else {
            return 0;
        }
    }

    public static function compareDesc(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $offset = self::getMilliseconds($dateTimeLeft) - self::getMilliseconds($dateTimeRight);
        if ($offset < 0) {
            return 1;
        } elseif ($offset > 0) {
            return -1;
        } else {
            return 0;
        }
    }

    /**
     * 返回两个日期在日历上的距离
     *
     * @param DateTime $dateTimeLeft
     * @param DateTime $dateTimeRight
     * @return int
     */
    public static function differenceInCalendarDays(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $startOfDateLeft = self::startOfDay($dateTimeLeft);
        $startOfDateRight = self::startOfDay($dateTimeRight);
        return (int)(($startOfDateLeft->getTimestamp() - $startOfDateRight->getTimestamp()) / self::SECONDS_IN_DAY);
    }

    public static function differenceInCalendarQuarters(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $offsetYears = (int)$dateTimeLeft->format('Y') - (int)$dateTimeRight->format('Y');
        return $offsetYears * 4 + self::getQuarter($dateTimeLeft) - self::getQuarter($dateTimeRight);
    }

    public static function differenceInCalendarMonths(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $yearAndMonthLeft = array_map('intval', explode('-', $dateTimeLeft->format('Y-m')));
        $yearAndMonthRight = array_map('intval', explode('-', $dateTimeRight->format('Y-m')));
        return ($yearAndMonthLeft[0] - $yearAndMonthRight[0]) * 12 + $yearAndMonthLeft[1] - $yearAndMonthRight[1];
    }

    public static function differenceInCalendarYears(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        return (int)($dateTimeLeft->format('Y')) - (int)($dateTimeRight->format('Y'));
    }

    public static function differenceInHours(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $dayOffset = self::differenceInCalendarDays($dateTimeLeft, $dateTimeRight);
        $hourOffset = (int)($dateTimeLeft->format('H')) - (int)($dateTimeRight->format('H'));
        return $dayOffset * 24 + $hourOffset - 1;
    }

    public static function differenceInDays(DateTime $dateTimeLeft, DateTime $dateTimeRight) : int {
        $dateTimeMillisecondsLeft = self::getMilliseconds($dateTimeLeft);
        $dateTimeMillisecondsRight = self::getMilliseconds($dateTimeRight);
        $offset = ($dateTimeMillisecondsLeft - $dateTimeMillisecondsRight) / self::MILLISECONDS_IN_DAY;
        return $offset > 0 ? floor($offset) : ceil($offset);
    }

    public static function getQuarter(DateTime $dateTime) : int {
        $month = (int)$dateTime->format('m');
        return (int)($month / 3 + 1);
    }

    public static function getMilliseconds(DateTime $dateTime) : float {
        return $dateTime->getTimestamp() * 1000 + (int)($dateTime->format('u'));
    }

    /**
     * 返回一天最开始的时间
     * @param DateTime $dateTime
     * @return DateTime
     */
    public static function startOfDay(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $clone->setTime(0, 0, 0, 0);
        return $clone;
    }

    public static function setISOWeekYear(DateTime $dateTime, int $dirtyAmount) : DateTime {
        return null;
    }

    public static function subDays(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $di = new DateInterval('P' . $dirtyAmount . 'D');
        $di->invert = 1;
        $clone->add($di);
        return $clone;
    }

    public static function subHours(DateTime $dateTime, int $dirtyAmount) : DateTime {
        $clone = clone $dateTime;
        $di = new DateInterval('PT' . $dirtyAmount . 'H');
        $di->invert = 1;
        $clone->add($di);
        return $clone;
    }

    public static function endOfDay(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $clone->setTime(23, 59, 59, 999999);
        return $clone;
    }

    public static function endOfDecade(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $year = (int)$dateTime->format('Y');
        $endOfDecadeYear = (int)($year / 10) * 10 + 9;
        $clone->setDate($endOfDecadeYear, 12, 31);
        $clone->setTime(23, 59, 59, self::UNIX_STAMP);
        return $clone;
    }

    public static function endOfHour(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $clone->setTime((int)$dateTime->format('H'), 59, 59, self::UNIX_STAMP);
        return $clone;
    }

    public static function endOfMonth(DateTime $dateTime) : DateTime {
        $year = (int)$dateTime->format('Y');
        $month = ((int)$dateTime->format('m') + 1) % 12;
        $endOfMonthDateTime = self::subDays(
            new DateTime($year . '-' . $month . '-01 23:59:59.' . self::UNIX_STAMP),
            1);
        return $endOfMonthDateTime;
    }

    public static function endOfMinute(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $clone->setTime(
            (int)$dateTime->format('H'),
            (int)$dateTime->format('i'),
            59,
            self::UNIX_STAMP
        );
        return $clone;
    }

    public static function endOfSecond(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $hour = (int)$dateTime->format('H');
        $minute = (int)$dateTime->format('i');
        $second = (int)$dateTime->format('s');
        $clone->setTime($hour, $minute, $second, self::UNIX_STAMP);
        return $clone;
    }

    public static function endOfWeek(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $year = (int)$dateTime->format('Y');
        $month = (int)$dateTime->format('m');
        $day = (int)$dateTime->format('d')
        + (((int)$dateTime->format('w') % 6 == 0)
            ? 0 : (6 - (int)$dateTime->format('w')));

        $clone->setDate($year, $month, $day);
        $clone->setTime(23, 59, 59, self::UNIX_STAMP);
        return $clone;
    }

    public static function endOfYear(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $year = (int)$dateTime->format('Y');
        $clone->setDate($year, 12, 31);
        $clone->setTime(23, 59, 59, self::UNIX_STAMP);
        return $clone;
    }

    public static function endOfQuarter(DateTime $dateTime) : DateTime {
        $clone = clone $dateTime;
        $year = (int)$dateTime->format('Y');
        $month = (int)$dateTime->format('m') - (int)$dateTime->format('m') % 3 + 3;
        $clone->setDate($year, $month, 1);
        $clone->setTime(23, 59, 59, self::UNIX_STAMP);

        return Utils::endOfMonth($clone);
    }

    public static function isMonday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '1';
    }

    public static function isTuesday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '2';
    }

    public static function isWednesday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '3';
    }

    public static function isThursday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '4';
    }

    public static function isFriday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '5';
    }

    public static function isSaturday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '6';
    }

    public static function isSunday(DateTime $dateTime) : bool {
        return $dateTime->format('w') === '0';
    }

    public static function isSameDay(DateTime $dateTimeLeft, DateTime $dateTimeRight) : bool {
        return $dateTimeLeft->format('Y-m-d') == $dateTimeRight->format('Y-m-d');
    }

    public static function isFirstDayOfMonth(DateTime $dateTime): bool {
        return $dateTime->format('Y-m-d')
            == (new DateTime($dateTime->format('Y-m-01')))->format('Y-m-d');
    }

    public static function isLastDayOfMonth(DateTime $dateTime): bool {
        $endOfMonthDateTime = self::endOfMonth($dateTime);
        return $endOfMonthDateTime->format('Y-m-d') == $dateTime->format('Y-m-d');
    }
}