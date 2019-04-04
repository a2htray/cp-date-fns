<?php
require_once __DIR__ .'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use A2htray\DateFNS\Utils;

class UtilsTest extends TestCase {
    public function testAddYears() {
        $dateTime = new DateTime('2019-04-10');
        $actualDateTime = Utils::addYears($dateTime, 8);
        $this->assertSame('2027-04-10', $actualDateTime->format('Y-m-d'));
    }

    public function testAddQuarters() {
        $dateTime = new DateTime('2019-04-10');
        $actualDateTime = Utils::addQuarters($dateTime, 1);
        $this->assertSame('2019-07-10', $actualDateTime->format('Y-m-d'));

        $actualDateTime = Utils::addQuarters($dateTime, 4);
        $this->assertSame('2020-04-10', $actualDateTime->format('Y-m-d'));
    }

    public function testAddMonths() {
        $dateTime = new DateTime('2019-04-10');
        $actualDateTime = Utils::addMonths($dateTime, 8);
        $this->assertSame('2019-12-10', $actualDateTime->format('Y-m-d'));

        $actualDateTime = Utils::addMonths($dateTime, 12);
        $this->assertSame('2020-04-10', $actualDateTime->format('Y-m-d'));
    }

    public function testAddWeeks() {
        $dateTime = new DateTime('2019-04-10');
        $this->assertSame('2019-04-17', Utils::addWeeks($dateTime, 1)->format('Y-m-d'));
    }

    public function testAddDays() {
        $dateTime = new DateTime('2019-04-02');
        $actualDateTime = Utils::addDays($dateTime, 8);
        $this->assertSame('2019-04-10', $actualDateTime->format('Y-m-d'));
    }

    /**
     * @param DateTime
     */
    public function testAddHours() {
        $dateTime = new DateTime('2019-04-10');
        // 加上 8 个小时
        $actualDateTime = Utils::addHours($dateTime, 8);
        $this->assertSame('2019-04-10 08', $actualDateTime->format('Y-m-d H'));
    }

    public function testAddMinutes() {
        $dateTime = new DateTime('2019-04-10');
        $actualDateTime = Utils::addMinutes($dateTime, 8);
        $this->assertSame('2019-04-10 08', $actualDateTime->format('Y-m-d i'));
    }

    public function testAddSeconds() {
        $dateTime = new DateTime('2019-04-10');
        $actualDateTime = Utils::addSeconds($dateTime, 8);
        $this->assertSame('2019-04-10 08', $actualDateTime->format('Y-m-d s'));
    }

//    public function testAddMilliseconds() {
//        $dateTime = new DateTime('2019-04-10 23:53:53.999991');
//        $actualDateTime = Utils::addMilliseconds($dateTime, 8);
//        $this->assertSame('2019-04-10 23:53.53.999999', $actualDateTime->format('Y-m-d H:i:s.u'));
//    }

    public function testCloseIndexTo() {
        $dateTime = new DateTime('2019-04-04');
        $dirtyDateArray = [
            new DateTime('2019-05-04'),
            new DateTime('2019-04-05'),
            new DateTime('2018-04-04'),
            new DateTime('2020-04-04'),
        ];
        $this->assertSame(1, Utils::closeIndexTo($dateTime, $dirtyDateArray));
    }

    public function testDifferenceInCalendarQuarters() {
        $dateTimeLeft = new DateTime('2014-06-02');
        $dateTimeRight = new DateTime('2013-11-31');
        $this->assertSame(2, Utils::differenceInCalendarQuarters($dateTimeLeft, $dateTimeRight));
    }

    public function testDifferenceInCalendarYears() {
        $dateTimeLeft = new DateTime('2014-06-02');
        $dateTimeRight = new DateTime('2013-11-31');
        $this->assertSame(1, Utils::differenceInCalendarYears($dateTimeLeft, $dateTimeRight));
    }

    public function testDifferenceInHours() {
        $dateTimeLeft = new DateTime('2014-06-02 19:00');
        $dateTimeRight = new DateTime('2014-06-02 06:50');
        $this->assertSame(12, Utils::differenceInHours($dateTimeLeft, $dateTimeRight));

        $dateTimeLeft = new DateTime('2014-06-03 19:00');
        $dateTimeRight = new DateTime('2014-06-02 06:50');
        $this->assertSame(36, Utils::differenceInHours($dateTimeLeft, $dateTimeRight));
    }

    public function testDifferenceInDays() {
        $dateTimeLeft = new DateTime('2012-06-02');
        $dateTimeRight = new DateTime('2011-06-02 23:59');
        $this->assertSame(365, Utils::differenceInDays($dateTimeLeft, $dateTimeRight));
    }
    public function testIsWhichDayInWeek() {
        $dt0 = new DateTime('2019-04-08');
        $dt1 = new DateTime('2019-04-09');
        $dt2 = new DateTime('2019-04-10');
        $dt3 = new DateTime('2019-04-11');
        $dt4 = new DateTime('2019-04-12');
        $dt5 = new DateTime('2019-04-13');
        $dt6 = new DateTime('2019-04-14');

        $this->assertSame(true, Utils::isMonday($dt0));
        $this->assertSame(true, Utils::isTuesday($dt1));
        $this->assertSame(true, Utils::isWednesday($dt2));
        $this->assertSame(true, Utils::isThursday($dt3));
        $this->assertSame(true, Utils::isFriday($dt4));
        $this->assertSame(true, Utils::isSaturday($dt5));
        $this->assertSame(true, Utils::isSunday($dt6));
    }

    public function testIsSameDay() {
        $dateTimeLeft = new DateTime('2019-04-03 12:00:00');
        $dateTimeRight = new DateTime('2019-04-03 02:00:00');
        $this->assertSame(true, Utils::isSameDay($dateTimeLeft, $dateTimeRight));

        $dateTimeRight = new DateTime('2019-04-04 02:00:00');
        $this->assertSame(false, Utils::isSameDay($dateTimeLeft, $dateTimeRight));
    }

    public function testIsFirstDayOfMonth() {
        $dateTime = new DateTime('2019-04-01 12:00:00');
        $this->assertSame(true, Utils::isFirstDayOfMonth($dateTime));

        $dateTime = new DateTime('2019-04-02 12:00:00');
        $this->assertSame(false, Utils::isFirstDayOfMonth($dateTime));
    }

    public function testEndOfMonth() {
        $dateTime = new DateTime('2019-04-23');
        $this->assertSame(
            '2019-04-30 23:59:59.999999',
            Utils::endOfMonth($dateTime)->format('Y-m-d H:i:s.u')
        );

        $dateTime = new DateTime('2019-05-23');
        $this->assertSame(
            '2019-05-31 23:59:59.999999',
            Utils::endOfMonth($dateTime)->format('Y-m-d H:i:s.u')
        );
    }

    public function testIsLastDayOfMonth() {
        $dateTime = new DateTime('2019-04-23');
        $this->assertSame(false, Utils::isLastDayOfMonth($dateTime));

        $dateTime = new DateTime('2019-04-30');
        $this->assertSame(true, Utils::isLastDayOfMonth($dateTime));
    }

    public function testEndOfDecade() {
        $dateTime = new DateTime('2014-05-04');
        $this->assertSame(
            '2019-12-31 23:59:59:999999',
            Utils::endOfDecade($dateTime)->format('Y-m-d H:i:s:u'));

        $dateTime = new DateTime('2019-08-04');
        $this->assertSame(
            '2019-12-31 23:59:59:999999',
            Utils::endOfDecade($dateTime)->format('Y-m-d H:i:s:u'));
    }

    public function testEndOfHour() {
        $dateTime = new DateTime('2019-08-04 23:47:11');
        $this->assertSame(
            '2019-08-04 23:59:59:999999',
            Utils::endOfHour($dateTime)->format('Y-m-d H:i:s:u'));
    }

    public function testEndOfDay() {
        $dateTime = new DateTime('2019-08-04 23:47:11');
        $this->assertSame(
            '2019-08-04 23:59:59:999999',
            Utils::endOfDay($dateTime)->format('Y-m-d H:i:s:u'));
    }

    public function testEndOfMinute() {
        $dateTime = new DateTime('2019-08-04 23:47:11');
        $this->assertSame(
            '2019-08-04 23:47:59.999999',
            Utils::endOfMinute($dateTime)->format('Y-m-d H:i:s.u'));
    }

    public function testEndOfSecond() {
        $dateTime = new DateTime('2019-08-04 23:47:11');
        $this->assertSame(
            '2019-08-04 23:47:11.999999',
            Utils::endOfSecond($dateTime)->format('Y-m-d H:i:s.u'));
    }

    public function testEndOfWeek() {
        $dateTime = new DateTime('2019-04-03 23:47:11');
        $this->assertSame(
            '2019-04-06 23:59:59.999999',
            Utils::endOfWeek($dateTime)->format('Y-m-d H:i:s.u'));
    }

    public function testEndOfYear() {
        $dateTime = new DateTime('2019-04-03 23:47:11');
        $this->assertSame(
            '2019-12-31 23:59:59.999999',
            Utils::endOfYear($dateTime)->format('Y-m-d H:i:s.u'));
    }

    public function testEndOfQuarter() {
        $dateTime = new DateTime('2019-04-03 23:47:11');
        $this->assertSame(
            '2019-06-30 23:59:59.999999',
            Utils::EndOfQuarter($dateTime)->format('Y-m-d H:i:s.u'));
    }
}