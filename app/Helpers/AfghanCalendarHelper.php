<?php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;

class AfghanCalendarHelper
{
    /**
     * Map Persian month names to Afghan calendar month names.
     */
    private static $afghanMonths = [
        'فروردین' => 'Hamal',
        'اردیبهشت' => 'Sowr',
        'خرداد' => 'Jowza',
        'تیر' => 'Saratan',
        'مرداد' => 'Asad',
        'شهریور' => 'Sunbuola',
        'مهر' => 'Mizan',
        'آبان' => 'Aqrab',
        'آذر' => 'Qaws',
        'دی' => 'Jadi',
        'بهمن' => 'Dalwa',
        'اسفند' => 'Hoot',
    ];

    /**
     * Convert a Gregorian date to Afghan calendar with Afghan month names.
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function toAfghanDate($date, $format = '%d %B %Y')
    {
        // Convert Gregorian date to Jalali
        $jalali = Jalalian::fromDateTime($date)->format($format);

        // Replace Persian month names with Afghan equivalents
        foreach (self::$afghanMonths as $persian => $afghan) {
            $jalali = str_replace($persian, $afghan, $jalali);
        }

        return $jalali;
    }
    public static function getAfghanMonth($date)
{
    $jalali = Jalalian::fromDateTime($date)->format('%B'); // Get only the month
    foreach (self::$afghanMonths as $persian => $afghan) {
        $jalali = str_replace($persian, $afghan, $jalali);
    }
    return $jalali; // Return Afghan month name
}
public static function toAfghanDateTime($datetime, $format = '%d %B %Y %I:%M %P')
    {
        // Convert Gregorian datetime to Jalali
        $jalaliDateTime = Jalalian::fromDateTime($datetime)->format($format);

        // Replace Persian month names with Afghan equivalents
        foreach (self::$afghanMonths as $persian => $afghan) {
            $jalaliDateTime = str_replace($persian, $afghan, $jalaliDateTime);
        }

        // Replace Persian AM/PM with English AM/PM
        $jalaliDateTime = str_replace(['ق.ظ', 'ب.ظ'], ['AM', 'PM'], $jalaliDateTime);

        return $jalaliDateTime; // Return Afghan date and time with 12-hour format and AM/PM in English
    }
}
