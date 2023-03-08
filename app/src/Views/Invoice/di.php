<?php



use App\Services\InvoiceService;
use App\Services\SalesTaxService;
use App\Services\PaymentGatewayService;
use App\Services\EmailService;
use App\Invoice\InvoiceController;

$invoiceController = new InvoiceController(
    new InvoiceService(
        new SalesTaxService(),
        new PaymentGatewayService(),
        new EmailService()
    )
);

echo $invoiceController->pay();

// $invoiceService = new InvoiceService(
//     new SalesTaxService(),
//     new PaymentGatewayService(),
//     new EmailService()
// );

