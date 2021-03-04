<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Controllers;


use App\Models\StreetModel;

class StreetController
{
    private StreetModel $model;

    public function __construct()
    {
        $this->model = new StreetModel();
    }

    public function all($page = 1): array
    {
        if (!is_numeric($page)) {
            return [400, '', ''];
        }
        $page = (int)$page;
        $page = $page > 0 ? $page : 1;
        return [200, 'Success', ['items' => $this->model->all($page)]];
    }
}