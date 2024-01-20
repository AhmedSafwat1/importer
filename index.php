<?php

require("vendor/autoload.php");

use Safwat\Importer\Classes\MockRepo;
use Safwat\Importer\Services\InvoiceService;
use Safwat\Importer\Repository\CustomerSqlRepository;
use Safwat\Importer\Repository\InvoiceSqlRepository;
use Safwat\Importer\Repository\ProductSqlRepository;

$customerRepo = Mockery::mock(CustomerSqlRepository::class);
$productRepo = Mockery::mock(ProductSqlRepository::class);
$invoiceRepo = Mockery::mock(InvoiceSqlRepository::class);

$mock = new MockRepo();
$mock->mockInvoice($invoiceRepo);



$invoiceService = new InvoiceService(
    $customerRepo,
    $invoiceRepo,
    $productRepo
);

$type = isset($_GET['type']) && in_array($_GET['type'], ["json","xml"]) ? $_GET['type'] : "json" ;

$data = $invoiceService->getAll(100, 0);

\Safwat\Importer\Classes\Response::$type($data);

