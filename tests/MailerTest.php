<?php
declare(strict_types=1);

namespace App\Test;

use App\Mailer\Exception\InvalidMessageType;
use App\Mailer\Exception\MessageBodyIsEmpty;
use App\Mailer\Exception\MessageCanNotBeSent;
use App\Mailer\MailerService;
use App\Mailer\Repository\MessageRepositoryInterface;
use App\Mailer\SenderResource\SenderResourceFactory;
use App\Test\Mock\MessageRepositoryMock;
use App\Test\Mock\OperatorRepositoryMock;
use App\Test\Mock\OrderRepositoryMock;
use App\Test\Mock\ProductRepositoryMock;
use PHPUnit\Framework\TestCase;

final class MailerTest extends TestCase
{
    private MailerService $mailerService;
    private MessageRepositoryInterface $messageRepository;

    public function setUp(): void
    {
        $this->messageRepository = new MessageRepositoryMock();
        $this->mailerService = new MailerService(
            $this->messageRepository,
            new SenderResourceFactory(
                new OperatorRepositoryMock(),
                new OrderRepositoryMock(),
                new ProductRepositoryMock()
            )
        );
    }

    /**
     * @param array $inputPayload
     * @param array $expectedOutput
     * @dataProvider getValidTestCases
     */
    public function testShouldSendMessage(array $inputPayload, array $expectedOutput): void
    {
        // When
        $this->mailerService->send([$inputPayload]);
        $sentMessage = $this->messageRepository->get($inputPayload['messageType'], $inputPayload['resource']);

        // Then
        self::assertEquals($sentMessage, $expectedOutput);
    }

    /**
     * @param array  $inputPayload
     * @param string $expectedMessage
     * @dataProvider getInvalidSenderAndTypePayloadTestCases
     */
    public function testShouldNotSendMessageBecauseSenderIsNotAllowedToSendThisTypeOfMessage(
        array $inputPayload,
        string $expectedMessage
    ): void {
        // Expect
        $this->expectException(MessageCanNotBeSent::class);
        $this->expectExceptionMessage($expectedMessage);

        // When
        $this->mailerService->send([$inputPayload]);
    }

    public function testShouldNotSendMessageBecauseMessageHasInvalidType(): void
    {
        // Expect
        $this->expectException(InvalidMessageType::class);

        // Given
        $input = [
            [
                'senderType'  => 3,
                'messageType' => 5,
                'resource'    => '34',
                'messageBody' => 'some message body',
                'senderEmail' => 'operator@email.com',
            ],
            [
                'senderType'  => 1,
                'messageType' => 1,
                'resource'    => '4',
                'messageBody' => 'some message body',
                'senderEmail' => 'sender@email.com',
            ]
        ];

        // When
        $this->mailerService->send($input);
    }

    /**
     * @throws InvalidMessageType
     * @throws MessageBodyIsEmpty
     * @throws MessageCanNotBeSent
     * @dataProvider getInvalidMessageBodyTestCases
     */
    public function testShouldNotSendMessageBecauseMessageBodyIsEmpty(array $input): void
    {
        // Expect
        $this->expectException(MessageBodyIsEmpty::class);

        // When
        $this->mailerService->send([$input]);
    }

    public function getValidTestCases(): array
    {
        return [
            [
                [
                    'senderType'  => 1,
                    'messageType' => 1,
                    'resource'    => '4',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'sender@email.com',
                ],
                [
                    'type'     => 1,
                    'resource' => '4',
                    'from'     => 'sender@email.com',
                    'to'       => 'some@seller.com',
                    'message'  => 'some message body',
                    'subject'  => 'Question about order no 4',
                ]
            ],
            [
                [
                    'senderType'  => 1,
                    'messageType' => 2,
                    'resource'    => '24asd4',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'sender@email.com',
                ],
                [
                    'type'     => 2,
                    'resource' => '24asd4',
                    'from'     => 'sender@email.com',
                    'to'       => 'some-other@seller.com',
                    'message'  => 'some message body',
                    'subject'  => 'Question about product no 24asd4',
                ]
            ],
            [
                [
                    'senderType'  => 2,
                    'messageType' => 1,
                    'resource'    => '4',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'sender@email.com',
                ],
                [
                    'type'     => 1,
                    'resource' => '4',
                    'from'     => 'sender@email.com',
                    'to'       => 'some@customer.com',
                    'message'  => 'some message body',
                    'subject'  => 'Question about order no 4',
                ]
            ],
            [
                [
                    'senderType'  => 2,
                    'messageType' => 2,
                    'resource'    => '24asd4',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'sender@email.com',
                ],
                [
                    'type'     => 2,
                    'resource' => '24asd4',
                    'from'     => 'sender@email.com',
                    'to'       => 'product@operator.com',
                    'message'  => 'some message body',
                    'subject'  => 'Question about product no 24asd4',
                ]
            ],
            [
                [
                    'senderType'  => 3,
                    'messageType' => 2,
                    'resource'    => '24asd4',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'operator@email.com',
                ],
                [
                    'type'     => 2,
                    'resource' => '24asd4',
                    'from'     => 'operator@email.com',
                    'to'       => 'some-other@seller.com',
                    'message'  => 'some message body',
                    'subject'  => 'Question about product no 24asd4',
                ]
            ]
        ];
    }

    public function getInvalidSenderAndTypePayloadTestCases(): iterable
    {
        yield 'operator not handle order type message' => [
                [
                    'senderType'  => 3,
                    'messageType' => 1,
                    'resource'    => '34',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'operator@email.com',
                ],
                'Cannot send message with type order from operator'
        ];
        yield 'unknown sender type' => [
                [
                    'senderType'  => 5,
                    'messageType' => 1,
                    'resource'    => '34',
                    'messageBody' => 'some message body',
                    'senderEmail' => 'operator@email.com',
                ],
                'Cannot send message with type order from unknown'
        ];
    }

    public function getInvalidMessageBodyTestCases(): iterable
    {
        yield 'message body is an empty string' => [
            [
                'senderType'  => 1,
                'messageType' => 1,
                'resource'    => '4',
                'messageBody' => '',
                'senderEmail' => 'some@email.com',
            ]
        ];
        yield 'message body after strip tags is an empty string' => [
            [
                'senderType'  => 1,
                'messageType' => 1,
                'resource'    => '4',
                'messageBody' => '<div><strong></strong></div>',
                'senderEmail' => 'some@email.com',
            ]
        ];
        yield 'message body contains only whitespaces' => [
            [
                'senderType'  => 1,
                'messageType' => 1,
                'resource'    => '4',
                'messageBody' => '    ',
                'senderEmail' => 'some@email.com',
            ]
        ];
    }
}
