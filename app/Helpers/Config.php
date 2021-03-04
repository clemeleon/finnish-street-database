<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 04 March 2021
 */

namespace App\Helpers;


class Config
{
    private static ?Config $config = null;

    private array $datas = [];

    private function __construct()
    {
        $this->load();
    }

    private function load(): void
    {
        $base = dirname(__DIR__, 2);
        $path = "$base/resources/config.php";
        if (file_exists($path)) {
            $datas = require_once "$path";
            if (is_array($datas)) {
                $datas['path'] = $base;
                $datas['resource-path'] = "$base/resources";
                $datas['app-path'] = "$base/app";
                if (isset($_SERVER['HTTPS']) &&
                    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
                    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
                    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
                    $datas['protocol'] = 'https://';
                } else {
                    $datas['protocol'] = 'http://';
                }
                $domain = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                if (strlen($domain) > 0 && (isset($datas['domain']) && strlen($datas['domain']) === 0)) {
                    $datas['domain'] = $domain;
                }
                $this->datas = $datas;
            }
        }
    }

    public static function init(): Config
    {
        if (is_null(self::$config)) {
            self::$config = new self();
        }
        return self::$config;
    }

    public function get(string $name, $def = null)
    {
        return (isset($this->datas[$name])) ? $this->datas[$name] : $def;
    }

    public function pick(array $names): array
    {
        $datas = [];
        foreach ($names as $name) {
            $datas[$name] = $this->get($name, '');
        }
        return $datas;
    }
}