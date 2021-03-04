<?php

declare(strict_types=1);
/**
 * Package: Street-Api.
 * 03 March 2021
 */

namespace App\Databases;


use App\Helpers\Config;
use App\Helpers\Helper;
use PDO;
use PDOException;

class Schema
{
    private PDO $db;

    /**
     * @var array|string[][]
     */
    private array $sql = [
        ['CREATE', 'TABLE IF NOT EXISTS `%s` (%s) CHARACTER SET utf8 COLLATE utf8_general_ci;'],
        ['INSERT', 'INTO %s %s VALUES %s;'],
        ['SELECT', '%s FROM %s %s %s;']
    ];

    public function __construct()
    {
        [
            'host' => $host, 'pass' => $pass,
            'name' => $name, 'username' => $username
        ] = Config::init()->get('database');
        $dsn = Helper::sprint('mysql:host=%s;dbname=%s', [$host, $name]);
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ];
        $this->db = new PDO($dsn, $username, $pass, $options);
    }

    public function create(string $table, array $datas): bool
    {
        $sql = implode(' ', $this->sql[0]);
        $id = '';
        $fields = [];
        foreach ($datas as $name => $type) {
            $key = strtolower($name);
            $fields[] = "`$key` $type";
            if (preg_match('/AUTO_INCREMENT/i', $type) && strlen($id) === 0) {
                $id = $key;
            }
        }
        if (strlen($id) > 0) {
            $fields[] = "PRIMARY KEY (`$id`)";
        }
        $field = trim(implode(', ', $fields));
        //var_dump($field);
        $sql = Helper::sprint($sql, [$table, $field]);
        //var_dump($sql);
        return ($this->db->exec($sql) !== false);
    }

    /**
     * @param string $table
     * @param array|string[] $columns array values only needed
     * @param array $where [[column, operator, value], [column, operator, value]]
     * @param array $limits [start, end]
     * @return array
     */
    public function select(string $table, array $columns = ['*'], array $where = [], array $limits = []): array
    {
        $sql = implode(' ', $this->sql[2]);
        $columns = array_map('strtolower', $columns);
        $condition = '';
        $limit = [];
        $values = [];
        if (!empty($where)) {
            $keys = [];
            foreach ($where as $wh) {
                [$key, $dill, $value] = $wh;
                $dill = trim($dill);
                $keys[] = "$key $dill :$key";
                $values[":$key"] = $value;
            }
            if (count($keys) === count($values)) {
                $condition = "WHERE " . implode(' AND ', $keys);
            }
        } else {
            $condition = '';
        }
        if (!empty($limits)) {
            foreach ($limits as $lim) {
                $lim = (int)$lim;
                if ($lim > 0) {
                    $limit[] = $lim;
                }
            }
        }
        $limit = implode(', ', $limit);
        if (strlen($limit) > 0) {
            $limit = "LIMIT $limit";
        }
        $sql = Helper::sprint(
            $sql,
            [implode(', ', $columns), $table, $condition, $limit]
        );
        $stmt = $this->db->prepare($sql);
        if (!empty($values)) {
            $stmt->execute($values);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $datas): array
    {
        $sql = implode(' ', $this->sql[1]);
        $keys = [];
        $values = [];
        $arrs = (Helper::isAssoc($datas)) ? [$datas] : $datas;
        foreach ($arrs as $arr) {
            if (empty($keys)) {
                $columns = array_keys(Helper::keyCase($arr));
                $keys = $this->keys($columns, ':%s');
                $sql = Helper::sprint(
                    $sql,
                    [$table, '(' . implode(', ', $columns) . ')', '(' . implode(', ', $keys) . ')']
                );
            }
            $values[] = array_combine($keys, array_values($arr));
        }
        return $this->save($sql, $values);
    }

    private function keys(array $keys, string $style): array
    {
        if (empty($keys)) {
            return $keys;
        }
        $content = [];
        foreach ($keys as $num => $key) {
            $content[] = Helper::sprint($style, [$key, $key]);
        }
        return $content;
    }

    private function save(string $sql, array $datas): array
    {
        $res = [];
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($sql);
            foreach ($datas as $key => $data) {
                if ($stmt->execute($data)) {
                    $res[$key] = $this->db->lastInsertId();
                }
            }
            $this->db->commit();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            var_dump($e->errorInfo);
            var_dump($datas);
        }
        return $res;
    }
}