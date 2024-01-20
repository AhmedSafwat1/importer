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
$mock->mockCustomer($customerRepo);
$mock->mockProduct($productRepo);
$mock->mockInvoice($invoiceRepo);

$filename = $argv[1];

if(!file_exists($filename)){
    echo "File path not exist". PHP_EOL;
    exit;
}



$invoiceService = new InvoiceService(
    $customerRepo,
    $invoiceRepo,
    $productRepo
);

$invoiceService->process($filename);