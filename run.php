#!/usr/bin/env php
<?php
# This file would be say, 'loading content into database'
//run-with  /var/www/html# php ./run.php
// 000*0 /usr/bin/php -f /var/www/html/run.php &> /dev/null

use App\Databases\Schema;
use App\Helpers\Helper;
use App\Data\Attribute;

require __DIR__ . '/vendor/autoload.php';
$start = Helper::monitor();
$datas = Helper::load('/mnt/c/www/html/BAF_20191116.dat');
$str = 'File not found or file was empty';
$table = 'streets';
if (!empty($datas)) {
    $schema = new Schema();
    $schema->create($table, Attribute::getColumns());
    $res = $schema->insert($table, $datas);
    if (!empty($res)) {
        $str = 'Data successfully inserted!';
    }
}
$end = Helper::monitor();
$total = $end - $start;
echo "\n$str\n $total\n";