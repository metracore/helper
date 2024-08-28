<?php

namespace Metracore\Helper;

use Exception;
use NumberFormatter;

use function max;
use function min;
use function rand;
use function round;
use function count;
use function array_sum;
use function is_numeric;
use function str_replace;
use function number_format;

/**
 * Number class for nitrovel framework helper component
 * Development Date : Aug 29, 2024
 */
class Number{


    /*
    | Format a number with grouped thousands.
    |
    | @param float $number
    | @param int $decimals
    | @param string $decimalPoint
    | @param string $thousandsSeparator
    | @return string
    */
    public function formatNumber($number, $decimals = 2, $decimalPoint = '.', $thousandsSeparator = ',') {
        return number_format($number, $decimals, $decimalPoint, $thousandsSeparator);
    }


    /*
    | Round a number to the nearest integer or to a specified precision.
    |
    | @param float $number
    | @param int $precision
    | @param int $mode
    | @return float
    */
    public function roundNumber($number, $precision = 0, $mode = PHP_ROUND_HALF_UP) {
        return round($number, $precision, $mode);
    }


    /*
    | Compare two numbers with a specified precision.
    |
    | @param float $number1
    | @param float $number2
    | @param int $precision
    | @return int 0 if equal, -1 if $number1 < $number2, 1 if $number1 > $number2
    */
    public function compareNumbers($number1, $number2, $precision = 2) {
        $difference = round($number1 - $number2, $precision);
        return $difference == 0 ? 0 : ($difference > 0 ? 1 : -1);
    }


    /*
    | Generate a random number within a specified range.
    |
    | @param int $min
    | @param int $max
    | @return int
    */
    public function generateRandomNumber($min = 0, $max = PHP_INT_MAX) {
        return rand($min, $max);
    }


    /*
    | Convert a number to its English word representation.
    |
    | @param int $number
    | @return string
    */
    public function numberToWords($number) {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($number);
    }


    /*
    | Check if a number is even.
    |
    | @param int $number
    | @return bool
    */
    public function isEven($number) {
        return $number % 2 === 0;
    }


    /*
    | Check if a number is odd.
    |
    | @param int $number
    | @return bool
    */
    public function isOdd($number) {
        return $number % 2 !== 0;
    }


    /*
    | Find the maximum value in an array of numbers.
    |
    | @param array $numbers
    | @return float|int|null
    */
    public function findMax(array $numbers) {
        return !empty($numbers) ? max($numbers) : null;
    }


    /*
    | Find the minimum value in an array of numbers.
    |
    | @param array $numbers
    | @return float|int|null
    */
    public function findMin(array $numbers) {
        return !empty($numbers) ? min($numbers) : null;
    }


    /*
    | Calculate the average of an array of numbers.
    |
    | @param array $numbers
    | @return float|null
    */
    public function calculateAverage(array $numbers) {
        return !empty($numbers) ? array_sum($numbers) / count($numbers) : null;
    }


    /*
    | Convert a number to a percentage format.
    |
    | @param float $number
    | @param int $decimals
    | @return string
    */
    public function toPercentage($number, $decimals = 2) {
        return $this->formatNumber($number * 100, $decimals) . '%';
    }


    /*
    | Calculate the percentage of a number.
    |
    | @param float $portion
    | @param float $total
    | @param int $decimals
    | @return string
    */
    public function calculatePercentage($portion, $total, $decimals = 2) {
        if ($total == 0) {
            return '0%';
        }
        return $this->toPercentage($portion / $total, $decimals);
    }


    /*
    | Convert a number to a specific base.
    |
    | @param int $number
    | @param int $base
    | @return string
    | @throws \Exception
    */
    public function convertToBase($number, $base = 10) {
        if ($base < 2 || $base > 36) {
            throw new Exception("Base must be between 2 and 36");
        }
        return base_convert($number, 10, $base);
    }


    /*
    | Validate if a string is a valid number.
    |
    | @param string $string
    | @return bool
    */
    public function isValidNumber($string) {
        return is_numeric($string);
    }


    /*
    | Convert a formatted string back to a float number.
    |
    | @param string $formattedNumber
    | @param string $decimalPoint
    | @param string $thousandsSeparator
    | @return float
    */
    public function parseFormattedNumber($formattedNumber, $decimalPoint = '.', $thousandsSeparator = ',') {
        $unformatted = str_replace([$thousandsSeparator, $decimalPoint], ['', '.'], $formattedNumber);
        return (float)$unformatted;
    }
}

?>
