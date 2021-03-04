<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Controllers;


use App\Helpers\Config;
use App\Models\StreetModel;

class StreetController
{
    private StreetModel $model;

    private Config $config;

    public function __construct()
    {
        $this->config = Config::init();
        $this->model = new StreetModel();
    }

    public function home(): array
    {
        $path = $this->config->get('path', '')."/resources/home.html";
        $data = '';
        if (file_exists($path)) {
            $data = file_get_contents($path);
        }
        return [200, 'Home', $data, true];
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