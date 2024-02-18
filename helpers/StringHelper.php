<?php

namespace app\helpers;

use Transliterator;

class StringHelper extends \yii\helpers\StringHelper {

    /**
     * Транслитерация строки
     * @param string $str Входная строка на русском
     * @return string
     */
    public static function transliterate($str) {
        return Transliterator::create('Russian-Latin/BGN; Any-Latin; Latin-ASCII;')->transliterate($str);
    }

    public static function detectEncoding($str) {
        $enclist = ['UTF-8', 'Windows-1251', 'Windows-1252', 'Windows-1254', 'KOI8-R',];
        return \mb_detect_encoding($str, $enclist);
    }

    /**
     * Удаление кавычек (одинарных и двойных) в строке
     * @param string $str Входная строка
     * @return string
     */
    public static function stripQuotes($str) {
        if (null === $str) {
            return $str;
        }
        return \str_replace(array('\'', '"'), '', $str);
    }

    /**
     * @param string $text
     *
     * @return string|null
     */
    public static function spaceToPercent(string $text = null) :?string
    {
        return \str_replace(' ', '%', addslashes(self::cleanQuery($text)));
    }

    /**
     * Очищаем строку запроса для исключения sql инъекций
     * @param $query
     *
     * @return string
     */
    public static function cleanQuery($query): string
    {
        $query = trim($query);
        $query = htmlspecialchars($query);
        $query = strip_tags($query);
        $query = preg_replace('~[^a-z0-9 \x80-\xFF]~i', '', $query);
        return $query;
    }

    /**
     * mb_stripos all occurences
     * based on http://www.php.net/manual/en/function.strpos.php#87061
     *
     * Find all occurrences of a needle in a haystack (case-insensitive, UTF8)
     *
     * @param string $haystack
     * @param string $needle
     * @return array|false
     */
    public static function mb_stripos_all($haystack, $needle) {
        $s = 0;
        $i = 0;
        $aStrPos = [];
        while (\is_int($i)) {
            $i = mb_stripos($haystack, $needle, $s);
            if (\is_int($i)) {
                $aStrPos[] = $i;
                $s = $i + mb_strlen($needle);
            }
        }

        if (\count($aStrPos) > 0) {
            return $aStrPos;
        }
        return false;
    }

    /**
     * Форматирует телефон в формат +7 (###) ###-##-##
     * @param $phone
     * @return bool|string
     */
    public static function asPhone($phone)
    {
        $format = ['10' => '+7 (###) ###-##-##'];
        $mask = '#';
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (is_array($format)) {
            if (array_key_exists(strlen($phone), $format)) {
                $format = $format[strlen($phone)];
            } else {
                return false;
            }
        }

        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }

    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucfirst()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @return string
     * @see https://secure.php.net/manual/en/function.ucfirst.php
     * @since 2.0.16
     */
    public static function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $rest;
    }

    /**
     * This method represent full FIO string as FIO with only inicials
     * example: Ivanov Ivan Ivanovich -> Ivanov I.I.
     * @param string $value the string to be proceeded
     * @return string
     */
    public static function asFIOWithInicials($value){
        return preg_replace('#(.*)\s+(.).*\s+(.).*#usi', '$1 $2.$3.', $value??"");
    }
}
