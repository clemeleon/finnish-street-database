<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Data;


use App\Interfaces\IData;

class Attribute implements IData
{
    public string $streetName;

    public string $streetNameAlt;

    public string $postCode;

    public string $city;

    public string $cityAlt;

    public string $minApartmentNo;

    public string $maxApartmentNo;

    public function __construct(array $datas)
    {
        $this->load($datas);
    }

    private function get(): array
    {
        return array_keys(get_class_vars(get_class($this)));
    }

    private function load(array $datas): void
    {
        $temps = array_change_key_case($datas, CASE_LOWER);
        $keys = $this->get();
        foreach ($keys as $key) {
            $k = strtolower($key);
            if (array_key_exists($k, $temps)) {
                $this->{$key} = $temps[$k];
            }
        }
    }

    public function getData(): array
    {
        $datas = [];
        $keys = $this->get();
        foreach ($keys as $key) {
            $datas[$key] = $this->{$key};
        }
        return $datas;
    }

    public static function getColumns(): array
    {
        return [
            'id' => 'INT AUTO_INCREMENT NOT NULL',
            'streetName' => 'VARCHAR(255) NULL',
            'streetNameAlt' => 'VARCHAR(255) NULL',
            'postCode' => 'VARCHAR(10) NULL',
            'city' => 'VARCHAR(255) NULL',
            'cityAlt' => 'VARCHAR(255) NULL',
            'minApartmentNo' => 'VARCHAR(10) NULL',
            'maxApartmentNo' => 'VARCHAR(10) NULL'
        ];
    }
}