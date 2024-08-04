<?php

/**
 * Dátum függvények
 * 
 * @author Kovács Zoltán <kovacs.zoltan@softc.hu>
 * @date 2023-08-01
 */

namespace App\Traits;

use Illuminate\Support\Carbon;

/**
 * Trait that provides functions for working with dates.
 */
trait DateTime
{
    /**
     * The default format for dates.
     *
     * @var string
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * The default localization for dates.
     *
     * @var string
     */
    protected $localization = 'hu';

    /**
     * The name of the timezone.
     *
     * @var string
     */
    protected $timezone_name = 'Europe/Budapest';

    /**
     * Get the current date in the given format.
     *
     * @param string|null $format The format of the date.
     * @return string The current date in the given format.
     */
    public function actualDate(string $format = null): string
    {
        if( is_null($format) ) { $format = $this->format; }
        
        return Carbon::now()->format($format);
    }

    /**
     * Get the start of the last week in the given format.
     *
     * @param string|null $format The format of the date.
     * @return string The start of the last week in the given format.
     */
    public function startLastWeek(string $format = null): string
    {
        if( is_null($format) ) { $format = $this->format; }
        
        return Carbon::now()->subWeek()->startOfWeek()->format($format);
    }

    /**
     * Get the end of the last week in the given format.
     *
     * @param string|null $format The format of the date.
     * @return string The end of the last week in the given format.
     */
    public function endOfLastWeek(string $format = null): string
    {
        if( is_null($format) ) { $format = $this->format; }
        
        return Carbon::now()->subWeek()->endOfWeek()->format($format);
    }

    /**
     * Compare two dates.
     *
     * @param string $date_01 The first date.
     * @param string $date_02 The second date.
     * @param string|null $format The format of the dates.
     * @return bool True if the dates are equal, false otherwise.
     */
    public function compareDates(string $date_01, string $date_02, string $format = null): bool
    {
        if( is_null($format) ){ $format = $this->format; }

        $d_date_01 = Carbon::createFromFormat($format, $date_01);
        $d_date_02 = Carbon::createFromFormat($format, $date_02);

        return ( $d_date_01 = $d_date_02 );
    }

    /**
     * Check if a date is a weekday.
     *
     * @param string $date The date to check.
     * @param string|null $localization The localization of the date.
     * @return bool True if the date is a weekday, false otherwise.
     */
    public function isWeekday(string $date, string $localization = null): bool
    {
        if( is_null($localization) ){ $localization = $this->localization; }

        return Carbon::parse($date)->isWeekday($localization);
    }

    /**
     * Check if a date is a weekend.
     *
     * @param string $date The date to check.
     * @param string|null $localization The localization of the date.
     * @return bool True if the date is a weekend, false otherwise.
     */
    public function isWeekend(string $date, string $localization = null): bool
    {
        if( is_null($localization) ){ $localization = $this->localization; }

        return Carbon::parse($date)->isWeekend($localization);
    }

    /**
     * Check if a given date string is valid according to a given format.
     *
     * @param string $date The date string to check.
     * @param string $format The format to use for checking the date string.
     * @return bool True if the date string is valid, false otherwise.
     */
    public function isDate(string $date, string $format): bool
    {
        // Initialize return value to true
        $retval = true;

        // Try to create a DateTime object from the date string and the given format
        if (\DateTime::createFromFormat($format, $date) === false) {
            // If the creation of the DateTime object fails, set return value to false
            $retval = false;
        }

        // Return the result
        return $retval;
    }
}
