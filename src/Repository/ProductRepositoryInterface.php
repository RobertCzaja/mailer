<?php


namespace App\Repository;


use App\Mailer\Exception\ProductNotFound;


interface ProductRepositoryInterface
{
    /**
     * @throws ProductNotFound
     */
    public function getSellerEmail(string $productId): string;
}