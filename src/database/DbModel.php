<?php

namespace kilyte\database;

use kilyte\Application;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (" . implode(",", $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public function update(array $set, array $where)
    {
        $tableName = $this->tableName();
        $attributes = array_keys($set);
        $select = array_keys($where);
        $sql = implode(",", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $select_from = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $select));
        $statement = self::prepare("UPDATE $tableName SET $sql WHERE $select_from");
        foreach ($set as $key => $item)
            $statement->bindValue(":$key", $item);

        foreach ($where as $key => $item)
            $statement->bindValue(":$key", $item);
        return $statement->execute();
    }

    protected static function prepare($sql): \PDOStatement
    {
        return Application::$app->db->prepare($sql);
    }

    public static function findOne(array $where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item)
            $statement->bindValue(":$key", $item);
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function find(array $where, array $columns = [], $limit = null)
    {

        $tableName = static::tableName();
        $columnList = "*";
        $limitList = 0;
        if (!empty($columns)) {
            $columnList = implode(",", $columns);
        }
        if (!empty($limit)) {
            if (is_array($limit))
                $limitList = implode(',', $limit);
            else
                $limitList = $limit;
        }

        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT $columnList FROM $tableName WHERE $sql LIMIT $limitList");
        if (empty($limitList))
            $statement = self::prepare("SELECT $columnList FROM $tableName WHERE $sql");
        foreach ($where as $key => $item)
            $statement->bindValue(":$key", $item);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAll(array $columns = [], $limit = null)
    {
        $tableName = static::tableName();
        $columnList = "*";
        $limitList = 0;
        if (!empty($columns)) {
            $columnList = implode(",", $columns);
        }
        if (!empty($limit)) {
            if (is_array($limit))
                $limitList = implode(',', $limit);
            else
                $limitList = $limit;
        }
        $statement = self::prepare("SELECT $columnList FROM $tableName LIMIT $limitList");
        if (empty($limitList))
            $statement = self::prepare("SELECT $columnList FROM $tableName");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPage(array $columns = null,  int $page = 1)
    {
        $limit = [0, 20];
        if ($page > 1) {
            $pe = $page * 20;
            $ps = $pe - 20;
            $limit = [$ps, $pe];
        }

        return  $this->getAll($columns, $limit);
    }

    public function has(array $where)
    {
        $results = self::findOne($where);
        return ($results) ? true : false;
    }
}
