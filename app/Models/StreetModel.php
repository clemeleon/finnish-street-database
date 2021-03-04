<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Models;


use App\Data\Street;
use App\Databases\Schema;
use Exception;

class StreetModel
{
    private Schema $schema;

    private int $total = 50;

    public function __construct()
    {
        $this->schema = new Schema();
    }

    public function all(int $page = 1): array
    {
        try {
            $datas = [];
            $end = $this->total * $page;
            $start = $end - $this->total;
            $temps = $this->schema->select('streets', ['*'], [], [$start, $end]);
            foreach ($temps as $temp) {
                $datas[] = new Street($temp);
            }
            return $datas;
        } catch (Exception $e) {
            return [500, '', ''];
        }
    }
}