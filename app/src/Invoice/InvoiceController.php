<?php

namespace App\Invoice;

use App\Services\InvoiceService;

class InvoiceController
{
    private $invoiceService;
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    public function pay()
    {
        $amount = 100;
        return $this->invoiceService->pay($amount);
    }

    public function store()
    {
        $name = 'test';
        $amount = 2;

        $this->invoiceService->process($name, $amount);
    }
}