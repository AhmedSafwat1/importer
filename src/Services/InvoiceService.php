<?php

namespace Safwat\Importer\Services;

use Exception;
use Safwat\Importer\Interfaces\CustomerRepositoryInterface;
use Safwat\Importer\Interfaces\ImportServiceInterface;
use Safwat\Importer\Interfaces\InvoiceRepositoryInterface;
use Safwat\Importer\Interfaces\ProductRepositoryInterface;

class InvoiceService implements ImportServiceInterface
{
    private array $products = [];

    private array $customers = [];

    private array $invoices = [];

    private array $rowsNotImport = [];

    public function __construct(
        public CustomerRepositoryInterface $customerRepo,
        public InvoiceRepositoryInterface $invoiceRepo,
        public ProductRepositoryInterface $productRepo,
    ) {
    }

    /**
     * @return void
     */
    public function process(string $file_path): mixed
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        foreach ($rows as $indx => $row) {
            if ($indx == 0) {
                continue;
            }
            $this->handleRawData($row);
        }

        echo 'Import Done , count of not import row :  '.count($this->rowsNotImport).PHP_EOL;

    }

    /**
     * Get All
     */
    public function getAll(int $limit, int $offset): mixed
    {
        $data = $this->invoiceRepo->getAll($limit, $offset);

        return $data;
    }

    /**
     * Handle Raw
     */
    public function handleRawData(array $raw): void
    {
        try {

            $raw = $this->cleanRaw($raw);
            $productId = $this->getProductId($raw[4]);
            $customerId = $this->getCustomerId($raw[2], $raw[3]);
            $invoiceId = $this->getInvoiceId($raw[0], $raw[1], $raw[8], $customerId);

            $data = [
                'product_id' => $productId,
                'invoiceId' => $invoiceId,
                'quantity' => $raw[5],
                'price' => $raw[6],
                'total' => $raw[7],
            ];

            $this->validationRaw($data);

            $this->invoiceRepo->addInvoiceProduct($data);
            echo sprintf('Add Raw ( %s )', implode(',', $raw)).PHP_EOL;

        } catch (\Exception $th) {
            array_push($this->rowsNotImport, ['raw' => $raw, 'message' => $th->getMessage()]);
        }
    }

    /**
     * Get product
     */
    public function getProductId(string $productName): mixed
    {
        if (isset($this->products[$productName])) {
            return $this->products[$productName];
        }
        $product = $this->productRepo->findByName($productName);
        if ($product) {
            $this->products[$productName] = $product['id'];

            return $product['id'];
        }

        return null;
    }

    /**
     * Get customer
     */
    public function getCustomerId(string $name, string $address): mixed
    {
        if (isset($this->customers[$name])) {
            return $this->customers[$name];
        }
        $customer = $this->customerRepo->findByName($name);
        if ($customer) {
            $this->customers[$name] = $customer['id'];

            return $customer['id'];
        }
        $id = $this->customerRepo->add([
            'name' => $name,
            'address' => $address,
        ]);
        $this->customers[$name] = $id;

        return $id;
    }

    /**
     * Get invoice
     */
    public function getInvoiceId(mixed $id, string $date, mixed $total, mixed $customerId): mixed
    {
        if (isset($this->invoices[$id])) {
            return $this->invoices[$id];
        }
        $invoice = $this->invoiceRepo->findById($id);
        if ($invoice) {
            $this->invoices[$id] = $invoice['id'];

            return $invoice['id'];
        }
        $id = $this->invoiceRepo->add([
            'id' => $id,
            'date' => $date,
            'grand_total' => $total,
            'customer_id' => $customerId,
        ]);
        $this->invoices[$id] = $id;

        return $id;
    }

    /**
     * Clean row
     */
    public function cleanRaw(array $raw): array
    {
        foreach ($raw as $key => $value) {
            $raw[$key] = trim($value);
        }

        return $raw;
    }

    /**
     * Validation
     */
    public function validationRaw(array $data): void
    {
        if (! isset($data['product_id'])) {
            throw new Exception('Product not found');
        }
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        $this->products = [];
        $this->invoices = [];
        $this->customers = [];
        $this->rowsNotImport = [];
    }
}
