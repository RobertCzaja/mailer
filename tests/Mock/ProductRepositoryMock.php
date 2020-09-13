<?php
declare(strict_types=1);


namespace App\Test\Mock;


use App\Mailer\Exception\ProductNotFound;
use App\Repository\ProductRepositoryInterface;

final class ProductRepositoryMock implements ProductRepositoryInterface
{
    private const SELLER_PRODUCTS = [
        [
            'productId'   => '345sd',
            'sellerEmail' => 'some@seller.com'
        ],
        [
            'productId'   => '24asd4',
            'sellerEmail' => 'some-other@seller.com'
        ],
        [
            'productId'   => '5',
            'sellerEmail' => 'some@seller.com'
        ],
    ];

    /**
     * @inheritDoc
     */
    public function getSellerEmail(string $productId): string
    {
        foreach (self::SELLER_PRODUCTS as $product) {
            if ($product['productId'] === $productId) {
                return $product['sellerEmail'];
            }
        }
        throw new ProductNotFound($productId);
    }
}