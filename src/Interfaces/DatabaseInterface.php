<?php

namespace Safwat\Importer\Interfaces;

/**
 * DatabaseInterface interface
 */
interface DatabaseInterface
{
    /**
     * @return bool
     */
    public function connect();

    /**
     * @return void
     */
    public function disconnect();

    /**
     * @param  string  $tableName
     * @param  array  $conditions
     * @param  array  $fields
     * @return mixed
     */
    public function update($tableName, $fields, $conditions);

    /**
     * Select
     *
     * @param string $tableName
     * @param array $columns
     * @param array $conditions
     * @param integer $limit
     * @param integer $offset
     * @param array $joinInfo
     * @return void
     */
    public function select(string $tableName, array $columns, array $conditions, int $limit = 1, int $offset = 0, $joinInfo = []);

    /**
     * @param  string  $tableName
     * @param  array  $columns
     * @param  array  $conditions
     * @param  array  $joinInfo
     * @return mixed
     */
    public function selectOne($tableName, $columns, $conditions, $joinInfo = []);

    /**
     * @param  string  $tableName
     * @param  array  $fields
     * @return mixed
     */
    public function insert($tableName, $fields);

    /**
     * Begin transaction
     *
     * @return void
     */
    public function beginTransaction();

    /**
     * Commit transaction
     *
     * @return void
     */
    public function commit();

    /**
     * Rollback transaction
     *
     * @return void
     */
    public function rollback();

    /**
     * Set connection
     *
     * @param  mixed  $connection
     */
    public function setConnection(mixed $connection):void;
}
