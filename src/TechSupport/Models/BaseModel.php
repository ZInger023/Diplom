<?php

namespace TechSupport\Models;

use TechSupport\Services\Db;

abstract class BaseModel
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return static[]
     */
public static function findAll(): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    public static function getLastInsertId()
    {
        $db = Db::getInstance();
        return $db->getLastInsertId();
    }

public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

public function saveToDb(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

protected function update(array $mappedProperties): void
{
    var_dump($mappedProperties);
    $columns2params = [];
    $params2values = [];
    $index = 1;
    foreach ($mappedProperties as $column => $value) {
        $param = ':param' . $index; // :param1
        $columns2params[] = $column . ' = ' . $param; // column1 = :param1
        $params2values[$param] = $value; // [:param1 => value1]
        $index++;
    }
    $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
    $db = Db::getInstance();
    $db->query($sql, $params2values, static::class);
}

private function insert(array $mappedProperties): void
{
    $filteredProperties = array_filter($mappedProperties);

    $columns = [];
    $paramsNames = [];
    $params2values = [];
    foreach ($filteredProperties as $columnName => $value) {
        $columns[] = '`' . $columnName. '`';
        $paramName = ':' . $columnName;
        $paramsNames[] = $paramName;
        $params2values[$paramName] = $value;
    }

    $columnsViaSemicolon = implode(', ', $columns);
    $paramsNamesViaSemicolon = implode(', ', $paramsNames);

    $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') VALUES (' . $paramsNamesViaSemicolon . ');';

    $db = Db::getInstance();
    $db->query($sql, $params2values, static::class);
    $this->id = $db->getLastInsertId();
} 

public function delete(): void
{
    $db = Db::getInstance();
    $db->query(
        'DELETE FROM `' . static::getTableName() . '` WHERE id = :id',
        [':id' => $this->id]
    );
    $this->id = null;
} 

private function mapPropertiesToDbFormat(): array
{
    $reflector = new \ReflectionObject($this);
    $properties = $reflector->getProperties();

    $mappedProperties = [];
    foreach ($properties as $property) {
        $propertyName = $property->getName();
        $mappedProperties[$propertyName] = $this->$propertyName;
    } 
    return  $mappedProperties;
}

    abstract protected static function getTableName(): string;

    public static function findElementByColumn(string $columnName, $value)
{
    $db = Db::getInstance();
    $result = $db->query(
        'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value;',
        [':value' => $value],
        static::class
    );
    if ($result === []) {
        return null;
    }
    return $result;
}
}