<?php
declare(strict_types=1);


namespace App\Mailer\SenderResource;


use App\Mailer\Model\Message;
use App\Repository\OrderRepositoryInterface;
use App\Repository\ProductRepositoryInterface;

final class CustomerSenderResource extends AbstractSenderResource
{
    private ProductRepositoryInterface $productRepository;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        int $messageType,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($messageType);
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public static function senderType(): int
    {
        return 1;
    }

    public static function senderName(): string
    {
        return 'customer';
    }

    /**
     * @inheritDoc
     */
    protected function messageTypeHandlers(): array
    {
        return [
            Message::TYPE_ORDER => function (string $orderId): string {
                return $this->orderRepository->getSellerEmail($orderId);
            },
            Message::TYPE_PRODUCT => function (string $productId): string {
                return $this->productRepository->getSellerEmail($productId);
            }
        ];
    }

}