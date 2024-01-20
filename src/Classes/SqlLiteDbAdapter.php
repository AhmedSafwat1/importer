<?php

namespace Safwat\Importer\Classes;

use PDO;
use Safwat\Importer\Interfaces\DatabaseInterface;
use Safwat\Importer\Interfaces\DatabaseTransactionInterface;

class SqlLiteDbAdapter implements DatabaseInterface, DatabaseTransactionInterface
{
    /**
     * File path
     *
     * @var string
     */
    private $file_path;

    /**
     * Pdo connection
     *
     * @var PDO
     */
    private $connection = null;

    /**
     * Constructor for sqlite
     */
    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
        $this->connect();
    }

    /**
     * @return bool
     */
    public function connect()
    {
        if (! $this->connection) {
            $this->connection = new PDO('sqlite:'.$this->file_path);
        }

        return true;
    }

    /**
     * Set connection
     *
     * @param  mixed  $connection
     * @return void
     */
    public function setConnection($connection):void
    {   
        $this->connection = $connection;
    }

    /**
     * @return void
     */
    public function disconnect()
    {
        return $this->connection = null;
    }

    /**
     * @param  string  $tableName
     * @param  array  $conditions
     * @param  array  $fields
     * @return mixed
     */
    public function update($tableName, $fields, $conditions)
    {
        $query = $this->createUpdateQuery($tableName, array_keys($fields));
        $query .= $this->addWhereSql($tableName, array_keys($conditions));
        $stm = $this->connection->prepare($query);
        $stm->execute(array_merge($fields, $conditions));

        return $stm->rowCount();
    }

    /**
     * Create update query
     *
     * @return string
     */
    private function createUpdateQuery(string $tableName, array $columns)
    {
        $query = "Update $tableName SET ";
        $dataSet = [];

        foreach ($columns as $key) {
            $dataSet[] = sprintf('%1$s=:%1$s', $key);
        }

        return $query.implode(' , ', $dataSet);
    }

   /**
    * Select function
    *
    * @param string $tableName
    * @param array $columns
    * @param array $conditions
    * @param integer $limit
    * @param integer $offset
    * @param array $joinInfo
    * @return mixed
    */
    public function select(string $tableName, array $columns, array $conditions, int $limit = 1, int $offset = 0, $joinInfo = [])
    {
        $selectColumns = $this->preparedColumnsToSelect($tableName, $columns);

        if (isset($joinInfo['select'])) {
            $selectColumns .= ', '.$joinInfo['select'];
        }
        $query = sprintf(
            '
            select %s 
            from %s
            %s
            %s
            LIMIT :limit OFFSET :offset  
        ',
            $selectColumns,
            $tableName,
            isset($joinInfo['join']) ? $joinInfo['join'] : '',
            $this->addWhereSql($tableName, array_keys($conditions))
        );
        $data = array_merge(
            $conditions,
            ['limit' => $limit, $offset]
        );
        $stmt = $this->connection->prepare($query);
        $stmt->execute($data);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();

    }

    /**
     * Add condition to query
     *
     * @param  string  $table
     * @param  array   $conditions
     * @param  string  $operation
     */
    private function addWhereSql(string $tableName, array $conditions, string $operation = '='): string
    {
        if (count($conditions) == 0) {
            return '';
        }

        $whereConditions = [];
        foreach ($conditions as $key) {
            $whereConditions[] = sprintf('%1$s.%2$s %3$s :%2$s', $tableName, $key, $operation);
        }
        $query = 'WHERE '.implode(' AND ', $whereConditions);

        return $query;
    }

    /**
     * Prepare Column to select
     *
     * @param  string  $tableName
     * @param  array  $columns
     * @return string
     */
    private function preparedColumnsToSelect($tableName, $columns = [])
    {

        if (count($columns) == 0) {
            return "$tableName.*";
        }
        $selectColumns = [];
        foreach ($columns as $key) {
            $selectColumns[] = sprintf('%s.%s', $tableName, $key);
        }

        return implode(',', $selectColumns);

    }

    /**
     * @param  string  $tableName
     * @param  array  $fields
     * @return mixed
     */
    public function insert($tableName, $fields)
    {
        $values = [];
        foreach (array_keys($fields) as $key) {
            $values[] = sprintf(':%s', $key);
        }

        $query = sprintf(
            'Insert into %s ( %s ) VALUES(%s)',
            $tableName,
            implode(',', array_keys($fields)),
            implode(',', $values)
        );
        $stmt = $this->connection->prepare($query);
        $stmt->execute($fields);

        return $this->connection->lastInsertId();

    }

    /**
     * Begin transaction
     *
     * @return void
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit transaction
     *
     * @return void
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Rollback transaction
     *
     * @return void
     */
    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * @param  string  $tableName
     * @param  array  $columns
     * @param  array  $conditions
     * @param  array  $joinInfo
     * @return mixed
     */
    public function selectOne($tableName, $columns, $conditions, $joinInfo = [])
    {
        $selectColumns = $this->preparedColumnsToSelect($tableName, $columns);

        if (isset($joinInfo['select'])) {
            $selectColumns .= ', '.$joinInfo['select'];
        }
        $query = sprintf(
            '
            select %s 
            from %s
            %s
            %s
        ',
            $selectColumns,
            $tableName,
            isset($joinInfo['join']) ? $joinInfo['join'] : '',
            $this->addWhereSql($tableName, array_keys($conditions))
        );
        $stmt = $this->connection->prepare($query);
        $stmt->execute($conditions);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $stmt->fetch();
    }
}
