<?php
require_once __DIR__ .'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use A2htray\DateFNS\Utils;

class UtilsTest extends TestCase {
    /**
     * @return DateTime
     */
    public function testAddDays() {
        $dateTime = new DateTime('2019-04-02');
        $actualDateTime = Utils::addDays($dateTime, 8);
        $this->assertSame('2019-04-10', $actualDateTime->format('Y-m-d'));

        return $actualDateTime;
    }

    /**
     * @param DateTime
     * @depends testAddDays
     */
    public function testAddHours(DateTime $dateTime) {
        // 加上 8 个小时
        $actualDateTime = Utils::addHours($dateTime, 8);
        $this->assertSame('2019-04-10 08', $actualDateTime->format('Y-m-d H'));
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