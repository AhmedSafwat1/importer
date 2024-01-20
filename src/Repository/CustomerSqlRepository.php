<?php

namespace Safwat\Importer\Repository;

use Safwat\Importer\Interfaces\CustomerRepositoryInterface;
use Safwat\Importer\Interfaces\DatabaseInterface;

class CustomerSqlRepository implements CustomerRepositoryInterface
{
    /**
     * Constructor for class
     */
    public function __construct(
        public DatabaseInterface $database,
        public string $table = 'cutomsers',
    ) {
    }

    public function findByName(string $name): mixed
    {
        return $this->database->selectOne($this->table, [], ['name' => $name]);
    }

    public function add(array $data): mixed
    {
        return $this->database->insert($this->table, $data);
    }
}
