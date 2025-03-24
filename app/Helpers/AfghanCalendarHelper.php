<?php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;

class AfghanCalendarHelper
{
    /**
     * Map Persian month names to Afghan calendar month names.
     */
    private static $afghanMonths = [
        'فروردین' => 'حمل',
        'اردیبهشت' => 'ثور',
        'خرداد' => 'جوزا',
        'تیر' => 'سرطان',
        'مرداد' => 'اسد',
        'شهریور' => 'سنبله',
        'مهر' => 'میزان',
        'آبان' => 'عقرب',
        'آذر' => 'قوس',
        'دی' => 'جدی',
        'بهمن' => 'دلو',
        'اسفند' => 'حوت',
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
    public static function toAfghanDateFormat($date, $format = 'Y/m/d')
    {
        // Convert Gregorian date to Jalali
        $jalali = Jalalian::fromDateTime($date)->format($format);

        return $jalali; // Return the date in the format like 1403/12/09
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
    
    public static function getCurrentShamsiMonthRange()
    {
        // Get current Jalali year and month
        $jalaliNow = Jalalian::now();
        $jalaliYear = $jalaliNow->getYear();
        $jalaliMonth = str_pad($jalaliNow->getMonth(), 2, '0', STR_PAD_LEFT);
       
        $startOfMonth = Jalalian::fromFormat('Y/m/d', "$jalaliYear/$jalaliMonth/01")->toCarbon();

    
        $daysInMonth = Jalalian::fromFormat('Y/m/d', "$jalaliYear/$jalaliMonth/01")->getMonthDays();

        $endOfMonth = Jalalian::fromFormat('Y/m/d', "$jalaliYear/$jalaliMonth/$daysInMonth")->toCarbon()->endOfDay();

        return [
            'start' => $startOfMonth,
            'end' => $endOfMonth,
        ];
    }
}
