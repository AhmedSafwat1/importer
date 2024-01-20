<?php

namespace Safwat\Importer\Repository;

use Safwat\Importer\Interfaces\DatabaseInterface;
use Safwat\Importer\Interfaces\ProductRepositoryInterface;

class ProductSqlRepository implements ProductRepositoryInterface
{
    /**
     * Constructor for class
     */
    public function __construct(
        public DatabaseInterface $database,
        public string $table = 'products',
    ) {
    }

    public function findByName(string $name): mixed
    {
        return $this->database->selectOne($this->table, [], ['name' => $name]);
    }
}
