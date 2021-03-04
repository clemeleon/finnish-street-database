<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Errors;


class InternalServerError extends Error
{
    public function __construct(string $title = '', string $detail = '')
    {
        $title = (strlen($title) > 3) ? $title : 'Internal server error';
        $detail = (strlen($detail) > 3) ? $detail : 'Please contact customer support';
        parent::__construct(500, $title, $detail);
    }
}