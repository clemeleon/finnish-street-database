<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Errors;


use App\Helpers\Helper;

abstract class Error
{
    public string $id;

    public int $status;

    public string $title;

    public string $detail;

    public function __construct(int $status, string $title, string $detail)
    {
        $this->id = Helper::guid();
        $this->status = $status;
        $this->title = $title;
        $this->detail = $detail;
    }
}