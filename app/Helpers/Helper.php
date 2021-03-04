<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Helpers;


class Helper
{
    public static function monitor(): float
    {
        return microtime(true);
    }

    public static function load(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $temps = self::lines($path);
        $datas = [];
        foreach ($temps as $i => $t) {
            $temp = utf8_encode($t);
            $code = self::sub($temp, 13, 5);
            $min = self::sub($temp, 187, 5);
            $max = self::sub($temp, 200, 5) . self::sub($temp, 206, 1) . self::sub($temp, 207, 5);
            $datas[] = [
                'streetName' => self::sub($temp, 102, 30),
                'streetNameAlt' => self::sub($temp, 132, 30),
                'postCode' => (strlen($code) > 0) ? $code : '00000',
                'city' => self::sub($temp, 18, 30),
                'cityAlt' => self::sub($temp, 48, 30),
                'minApartmentNo' => (strlen($min) > 0) ? $min : '0',
                'maxApartmentNo' => (strlen($max)) ? $max : '0'
            ];
        }
        return $datas;
    }

    public static function sub(string $str, int $start, int $end): string
    {
        $value = substr(trim($str), $start, $end);
        return (!is_string($value)) ? '' : trim($value);
    }

    /**
     * @param string $path
     * @return iterable
     */
    public static function lines(string $path): iterable
    {
        $handle = fopen($path, "r");

        while (!feof($handle)) {
            $value = fgets($handle);
            $str = (!is_string($value)) ? '' : $value;
            yield trim($str);
        }

        fclose($handle);
    }

    public static function guid(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $rand = random_bytes(16);
        assert(strlen($rand) == 16);

        // Set version to 0100
        $rand[6] = chr(ord($rand[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $rand[8] = chr(ord($rand[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($rand), 4));
    }

    /**
     * Check if an array is associative or sequential
     * @param array $arr
     * @return bool assoc: true, seq: false
     */
    public static function isAssoc(array $arr): bool
    {
        return count(array_filter(array_keys($arr), 'is_string')) > 0;
    }

    public static function sprint(string $str, array $data): string
    {
        return sprintf($str, ...$data);
    }

    public static function keyCase(array $arr, bool $low = true): array
    {
        return array_change_key_case($arr, ($low) ? CASE_LOWER : CASE_UPPER);
    }
}