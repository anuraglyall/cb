<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

function number_to_words($number)
{
    $words = array(
        '0' => 'zero',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine'
    );

    // If the number is in the teens, handle it separately
    $teens = array(
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' => 'nineteen'
    );

    // Tens multiples
    $tens = array(
        '10' => 'ten',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety'
    );

    // Suffixes for large numbers (thousand, million, billion)
    $suffixes = array('', 'thousand', 'million', 'billion');

    $words_in_number = array();
    $suffix_index = 0;

    // Handle large numbers
    while ($number > 0) {
        $thousands = $number % 1000;

        if ($thousands > 0) {
            if ($thousands == 1 && $suffix_index > 0) {
                $words_in_number[] = $suffixes[$suffix_index - 1];
            } else {
                $words_in_number[] = number_to_words($thousands) . ' ' . $suffixes[$suffix_index];
            }
        }

        $number = floor($number / 1000);
        $suffix_index++;
    }

    // Concatenate the words
    $result = implode(' ', array_reverse($words_in_number));

    return $result;
}
