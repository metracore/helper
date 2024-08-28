<?php

namespace Metracore\Helper;

use DateTime;
use Exception;
use DateTimeZone;
use DateInterval;
use IntlDateFormatter;

use function date;

/**
 * Time class for nitrovel framework helper component
 * Development Date : Aug 29, 2024
 */
class Time{


    /*
    |   Get the current date and time in the specified format.
    |
    |   @param string $format
    |   @return string
    */
    public function getCurrentDateTime($format = 'Y-m-d H:i:s') {
        return date($format);
    }


    /*
    | Convert a date/time string from one format to another.
    |
    | @param string $dateTime
    | @param string $fromFormat
    | @param string $toFormat
    | @return string
    | @throws \Exception
    */
    public function convertFormat($dateTime, $fromFormat = 'Y-m-d H:i:s', $toFormat = 'd-m-Y H:i:s') {
        $date = DateTime::createFromFormat($fromFormat, $dateTime);
        if ($date) {
            return $date->format($toFormat);
        } else {
            throw new Exception("Invalid date format");
        }
    }


    /*
    | Calculate the difference between two dates.
    |
    | @param string $startDate
    | @param string $endDate
    | @return \DateInterval
    */
    public function dateDifference($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        return $start->diff($end);
    }


    /*
    | Convert a date/time string to a different timezone.
    |
    | @param string $dateTime
    | @param string $fromTimezone
    | @param string $toTimezone
    | @param string $format
    | @return string
    | @throws \Exception
    */
    public function convertTimezone($dateTime, $fromTimezone = 'UTC', $toTimezone = 'UTC', $format = 'Y-m-d H:i:s') {
        try {
            $date = new DateTime($dateTime, new DateTimeZone($fromTimezone));
            $date->setTimezone(new DateTimeZone($toTimezone));
            return $date->format($format);
        } catch (Exception $e) {
            throw new Exception("Error in timezone conversion: " . $e->getMessage());
        }
    }


    /*
    | Get the timestamp for a given date/time string.
    |
    | @param string $dateTime
    | @param string $format
    | @return int
    | @throws \Exception
    */
    public function getTimestamp($dateTime, $format = 'Y-m-d H:i:s') {
        $date = DateTime::createFromFormat($format, $dateTime);
        if ($date) {
            return $date->getTimestamp();
        } else {
            throw new Exception("Invalid date/time format");
        }
    }


    /*
    | Add an interval to a date.
    |
    | @param string $dateTime
    | @param string $intervalSpec
    | @param string $format
    | @return string
    | @throws \Exception
    */
    public function addInterval($dateTime, $intervalSpec, $format = 'Y-m-d H:i:s') {
        try {
            $date = new DateTime($dateTime);
            $date->add(new DateInterval($intervalSpec));
            return $date->format($format);
        } catch (Exception $e) {
            throw new Exception("Error in adding interval: " . $e->getMessage());
        }
    }


    /*
    | Subtract an interval from a date.
    |
    | @param string $dateTime
    | @param string $intervalSpec
    | @param string $format
    | @return string
    | @throws \Exception
    */
    public function subtractInterval($dateTime, $intervalSpec, $format = 'Y-m-d H:i:s') {
        try {
            $date = new DateTime($dateTime);
            $date->sub(new DateInterval($intervalSpec));
            return $date->format($format);
        } catch (Exception $e) {
            throw new Exception("Error in subtracting interval: " . $e->getMessage());
        }
    }


    /*
    | Get the time elapsed from a given date in a human-readable format.
    |
    | @param string $dateTime
    | @return string
    | @throws \Exception
    */
    public function timeElapsed($dateTime) {
        try {
            $date = new DateTime($dateTime);
            $now = new DateTime();
            $diff = $now->diff($date);

            if ($diff->y > 0) {
                return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
            } elseif ($diff->m > 0) {
                return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
            } elseif ($diff->d > 0) {
                return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
            } elseif ($diff->h > 0) {
                return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
            } elseif ($diff->i > 0) {
                return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
            } else {
                return 'just now';
            }
        } catch (Exception $e) {
            throw new Exception("Error in calculating time elapsed: " . $e->getMessage());
        }
    }


    /*
    | Check if a date is in the past.
    |
    | @param string $dateTime
    | @return bool
    | @throws \Exception
    */
    public function isPast($dateTime) {
        try {
            $date = new DateTime($dateTime);
            $now = new DateTime();
            return $date < $now;
        } catch (Exception $e) {
            throw new Exception("Error in checking if date is past: " . $e->getMessage());
        }
    }


    /*
    | Check if a date is in the future.
    |
    | @param string $dateTime
    | @return bool
    | @throws \Exception
    */
    public function isFuture($dateTime) {
        try {
            $date = new DateTime($dateTime);
            $now = new DateTime();
            return $date > $now;
        } catch (Exception $e) {
            throw new Exception("Error in checking if date is future: " . $e->getMessage());
        }
    }


    /*
    | Validate if a given date/time string matches a specified format.
    |
    | @param string $dateTime
    | @param string $format
    | @return bool
    */
    public function isValidDateTime($dateTime, $format = 'Y-m-d H:i:s') {
        $date = DateTime::createFromFormat($format, $dateTime);
        return $date && $date->format($format) === $dateTime;
    }


    /*
    | Get a list of supported time zones.
    |
    | @return array
    */
    public function getTimezones() {
        return DateTimeZone::listIdentifiers();
    }


    /*
    | Format a date based on locale settings.
    |
    | @param string $dateTime
    | @param string $locale
    | @return string
    */
    public function formatDateByLocale($dateTime, $locale = 'en_US') {
        $date = new DateTime($dateTime);
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        return $formatter->format($date);
    }
}

?>
