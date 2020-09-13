<?php
declare(strict_types=1);


namespace App\Mailer\SenderResource;


use App\Mailer\Model\Message;
use App\Repository\ProductRepositoryInterface;

final class OperatorSenderResource extends AbstractSenderResource
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(int $messageType, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($messageType);
        $this->productRepository = $productRepository;
    }

    public static function senderType(): int
    {
        return 3;
    }

    public static function senderName(): string
    {
        return 'operator';
    }

    /**
     * @inheritDoc
     */
    protected function messageTypeHandlers(): array
    {
        return [
            Message::TYPE_PRODUCT => function (string $productId): string {
                return $this->productRepository->getSellerEmail($productId);
            }
        ];
    }
}