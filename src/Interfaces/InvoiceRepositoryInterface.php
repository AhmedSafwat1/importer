<?php

namespace Safwat\Importer\Interfaces;

interface InvoiceRepositoryInterface
{
    public function findById(mixed $id):mixed;

    public function add(array $data):mixed;

    public function addInvoiceProduct(array $data):mixed;

    public function getAll(int $limit,int $offset):mixed;
}
