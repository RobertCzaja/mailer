<?php
declare(strict_types=1);


namespace App\Test\Mock;


use App\Mailer\Exception\OrderNotFound;
use App\Repository\OrderRepositoryInterface;


final class OrderRepositoryMock implements OrderRepositoryInterface
{
    private const ORDERS = [
        [
            'orderId'       => '4',
            'customerEmail' => 'some@customer.com',
            'sellerEmail'   => 'some@seller.com'
        ],
        [
            'productId'     => '566',
            'customerEmail' => 'some2@customer.com',
            'sellerEmail'   => 'some-other@seller.com'
        ],
        [
            'productId'     => '3456',
            'customerEmail' => 'some3@customer.com',
            'sellerEmail'   => 'some@seller.com'
        ],
    ];

    /**
     * @inheritDoc
     */
    public function getSellerEmail(string $orderId): string
    {
        foreach (self::ORDERS as $order) {
            if ($order['orderId'] === $orderId) {
                return $order['sellerEmail'];
            }
        }
        throw new OrderNotFound($orderId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerEmail(string $orderId): string
    {
        foreach (self::ORDERS as $order) {
            if ($order['orderId'] === $orderId) {
                return $order['customerEmail'];
            }
        }
        throw new OrderNotFound($orderId);
    }
}