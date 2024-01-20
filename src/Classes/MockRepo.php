<?php

namespace Safwat\Importer\Classes;

class MockRepo
{
    public $customerData = [
        [
            'id' => 1,
            'name' => 'Idaline Mateuszczyk',
            'address' => '95798 Fieldstone Point',
        ],
        [
            'id' => 2,
            'name' => 'Neill Manz',
            'address' => '7 Commercial Road',
        ],
        [
            'id' => 3,
            'name' => 'Alli Decker',
            'address' => '09 Rieder Terrace',
        ],
    ];

    public $productData = [
        [
            'id' => 1,
            'name' => 'Bread - Granary Small Pull',
            'price' => 10.4,
        ],
        [
            'id' => 2,
            'name' => 'Soup - Knorr, Ministrone',
            'price' => 10.4,
        ],
        [
            'id' => 3,
            'name' => 'Pepper - Green Thai',
            'price' => 10.4,
        ],
        [
            'id' => 4,
            'name' => 'Chicken - Wieners',
            'price' => 10.4,
        ],
    ];

    public $invoice = [
        [
            'id' => 1,
            'date' => '01/01/2020',
            'grand_total' => 55,
            'customer_id' => 1,
        ],
        [
            'id' => 2,
            'date' => '01/01/2020',
            'grand_total' => 55,
            'customer_id' => 3,

        ],
        [
            'id' => 3,
            'date' => '01/01/2020',
            'grand_total' => 55,
            'customer_id' => 3,
        ],
    ];

    public $invoices = [
        [
            'id' => 1,
            'date' => '01/01/2020',
            'grand_total' => 55,
            'customer_name' => 'Neill Manz',
            'customer_address' => '7 Commercial Road',
            'product_name' => 'Bread - Granary Small Pull',
            'product_price' => 10.4,
            'product_quantity' => 1,
            'product_total' => 1,

        ],
    ];

    public function mockCustomer(&$mock): void
    {
        $mock->allows()->findByName($this->customerData[0]['name'])->andReturns($this->customerData[0]);
        $mock->allows()->findByName($this->customerData[1]['name'])->andReturns($this->customerData[1]);
        $mock->allows()->findByName($this->customerData[2]['name'])->andReturns(false);
        $data = $this->customerData[2];
        unset($data['id']);
        $mock->allows()->add($data)->andReturns($this->customerData[2]['id']);

    }

    public function mockProduct(&$mock): void
    {
        $mock->allows()->findByName($this->productData[0]['name'])->andReturns($this->productData[0]);
        $mock->allows()->findByName($this->productData[1]['name'])->andReturns($this->productData[1]);
        $mock->allows()->findByName($this->productData[2]['name'])->andReturns($this->productData[1]);
        $mock->allows()->findByName($this->productData[3]['name'])->andReturns(false);

    }

    public function mockInvoice(&$mock): void
    {
        $mock->allows()->findById($this->invoice[0]['id'])->andReturns($this->invoice[0]);
        $mock->allows()->findById($this->invoice[1]['id'])->andReturns($this->invoice[1]);
        $mock->allows()->findById($this->invoice[2]['id'])->andReturns(false);
        $mock->allows(['add' => $this->invoice[2]['id']]);
        $mock->allows(['addInvoiceProduct' => 1]);
        $mock->allows(['getAll' => $this->invoices]);

    }
}
