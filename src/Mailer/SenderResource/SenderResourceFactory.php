<?php
declare(strict_types=1);


namespace App\Mailer\SenderResource;


use App\Mailer\Exception\MessageCanNotBeSent;
use App\Repository\OperatorRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\ProductRepositoryInterface;

final class SenderResourceFactory
{
    private OperatorRepositoryInterface $operatorRepository;
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        OperatorRepositoryInterface $operatorRepository,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->operatorRepository = $operatorRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return SenderResourceInterface
     * @throws MessageCanNotBeSent
     */
    public function getInstance(?int $senderType, int $messageType): SenderResourceInterface
    {
        switch ($senderType) {
            case CustomerSenderResource::senderType():
                return new CustomerSenderResource($messageType, $this->productRepository, $this->orderRepository);
            case SellerSenderResource::senderType():
                return new SellerSenderResource($messageType, $this->orderRepository, $this->operatorRepository);
            case OperatorSenderResource::senderType():
                return new OperatorSenderResource($messageType, $this->productRepository);
            default:
                throw new MessageCanNotBeSent($messageType, $senderType);
        }
    }

    public static function getSenderTypeName(int $senderType): string
    {
        switch ($senderType) {
            case CustomerSenderResource::senderType():
                return CustomerSenderResource::senderName();
            case SellerSenderResource::senderType():
                return SellerSenderResource::senderName();
            case OperatorSenderResource::senderType():
                return OperatorSenderResource::senderName();
            default:
                return 'unknown';
        }
    }

}