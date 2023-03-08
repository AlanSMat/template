<?php
namespace App\Services;

class InvoiceService
{

    public function __construct(
        protected SalesTaxServiceInterface $salesTaxService,
        protected PaymentGatewayService $paymentGatewayService,
        protected EmailService $emailService
    )
    {
        $this->salesTaxService = $salesTaxService;        
    }

    public function pay($amount) : string
    {
        $tax = $this->salesTaxService->calculateTax($amount);
        return $tax;      
    }
}
