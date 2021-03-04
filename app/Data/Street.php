<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Data;


use App\Interfaces\IData;

class Street implements IData
{
    public $id;

    public string $type = 'Street';

    public Attribute $attributes;

    public function __construct(array $datas)
    {
        $this->id = (isset($datas['id'])) ? $datas['id'] : 0;
        $this->attributes = new Attribute($datas);
    }

    public function getData(): array
    {
        $datas = [];
        foreach ($this as $key => $value) {
            $datas[$key] = ($value instanceof Attribute) ? (object)$value->getData() : $value;
        }
        return $datas;
    }
}