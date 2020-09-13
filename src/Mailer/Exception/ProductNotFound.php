<?php
declare(strict_types=1);

namespace App\Mailer\Exception;

class ProductNotFound extends \Exception
{
    public function __construct(string $productId)
    {
        parent::__construct('Product ' . $productId . ' not found');
    }
}
