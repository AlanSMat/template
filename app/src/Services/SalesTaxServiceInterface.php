<?php

namespace App\Services;

interface SalesTaxServiceInterface
{
    public function calculateTax($amount) : string;
}