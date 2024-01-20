<?php

namespace Safwat\Importer\Repository;

use Safwat\Importer\Interfaces\DatabaseInterface;
use Safwat\Importer\Interfaces\InvoiceRepositoryInterface;

class InvoiceSqlRepository implements InvoiceRepositoryInterface
{
    /**
     * Constructor for class
     */
    public function __construct(
        public DatabaseInterface $database,
        public string $invoiceTable = 'invoices',
        public string $invoiceProductTable = 'invoice_products',
    ) {
    }

    public function findById(mixed $id):mixed
    {
        return $this->database->selectOne($this->invoiceTable, [], ['id' => $id]);
    }

    public function add(array $data):mixed
    {
        return $this->database->insert($this->invoiceTable, $data);
    }

    public function addInvoiceProduct(array $data):mixed
    {
        return $this->database->insert($this->invoiceProductTable, $data);
    }

    public function getAll(int $limit, int $offset):mixed
    {
        return $this->database->select($this->invoiceTable, [
            'id',
            'date',
            'grand_total',
        ], [], $limit, $offset, [
            'select' => [
                'customers.name as customer_name',
                'customers.address as customer_address',
                'products.name as product_name',
                'invoice_products.quantity as product_quantity ',
                'invoice_products.price as product_price',
                'invoice_products.total as product_total',

            ],
            'join' => [
                '
            Left JOIN invoice_products on invoices.id =  invoice_products.invoice_id
            INNER JOIN products on products.id =  invoice_products.product_id
            INNER JOIN customers on customers.id =  invoices.customer_id
            ',
            ],
        ]);
    }
}
