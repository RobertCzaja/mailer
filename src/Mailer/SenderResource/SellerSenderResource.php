<?php
declare(strict_types=1);


namespace App\Mailer\SenderResource;



use App\Mailer\Model\Message;
use App\Repository\OperatorRepositoryInterface;
use App\Repository\OrderRepositoryInterface;

final class SellerSenderResource extends AbstractSenderResource
{
    private OrderRepositoryInterface $orderRepository;
    private OperatorRepositoryInterface $operatorRepository;

    public function __construct(
        int $messageType,
        OrderRepositoryInterface $orderRepository,
        OperatorRepositoryInterface $operatorRepository
    ) {
        parent::__construct($messageType);
        $this->orderRepository = $orderRepository;
        $this->operatorRepository = $operatorRepository;
    }

    public static function senderType(): int
    {
        return 2;
    }

    public static function senderName(): string
    {
        return 'seller';
    }

    /**
     * @inheritDoc
     */
    protected function messageTypeHandlers(): array
    {
        return [
            Message::TYPE_ORDER => function (string $orderId): string {
                return $this->orderRepository->getCustomerEmail($orderId);
                //return $this->storage->getCustomerEmailByOrder($orderId);
            },
            Message::TYPE_PRODUCT => function (): string {
                return $this->operatorRepository->getOperatorEmailByType(Message::TYPE_PRODUCT);
                //return $this->storage->getOperatorEmailByType(Message::TYPE_PRODUCT);
            }
        ];
    }
}