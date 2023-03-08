<?php
namespace App\Services;

class SalesTaxService implements SalesTaxServiceInterface
{    
    public function __construct()
    {
        echo 'SalesTax service';
        // connect to SalesTax service
    }

    public function calculateTax($amount) : string
    {
        return "<p>Taxable amount for $amount is "  . $amount * 0.3 . "</p>";
    }
}