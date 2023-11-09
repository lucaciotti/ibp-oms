<?php

namespace App\Helpers;

use DateInterval;
use DatePeriod;
use DateTime;

class DatetimeHelper {
    public static function getFirstDayOfMonth($monthName)
    {
        return new DateTime('first day of ' . $monthName . ' this year');
    }

    public static function getLastDayOfMonth($monthName)
    {
        return new DateTime('last day of ' . $monthName . ' this year');
    }

    public static function getFirstWeekOfMonth($monthName){
        $firstDay = DatetimeHelper::getFirstDayOfMonth($monthName);
        $weekNum = $firstDay->format('W');
        $yearNum = $firstDay->format('Y');
        $dto = new DateTime();
        $dto->setISODate($yearNum, $weekNum);
        return $dto;
    }

    public static function getLastWeekOfMonth($monthName){
        $firstDay = DatetimeHelper::getLastDayOfMonth($monthName);
        $weekNum = $firstDay->format('W');
        $yearNum = $firstDay->format('Y');
        $dto = new DateTime();
        $dto->setISODate($yearNum, $weekNum);
        $dto->modify('+6 days');
        return $dto;
    }

    public static function getDateWeekPeriod($startdate, $endDate) {
        $interval = new DateInterval('P1W');
        $period   = new DatePeriod($startdate, $interval, $endDate);
        return $period;
    }

    public static function getDateWeekPeriodByMonth($monthName=null) {
        if (empty($monthName)) $monthName = strtolower((new DateTime())->format('F'));
        $startdate = DatetimeHelper::getFirstWeekOfMonth($monthName);
        $endDate = DatetimeHelper::getLastWeekOfMonth($monthName);
        return DatetimeHelper::getDateWeekPeriod($startdate, $endDate);
    }
}
