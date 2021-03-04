<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Errors;


class BadRequestError extends Error
{
    public function __construct(string $title = '', string $detail = '')
    {
        $title = (strlen($title) > 3) ? $title : 'Parameter not given';
        $detail = (strlen($detail) > 3) ? $detail : 'Parameter (search) not given or empty';
        parent::__construct(400, $title, $detail);
    }
}