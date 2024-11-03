<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Planet\InterviewChallenge\Domain\Shop\CartItem;
use Planet\InterviewChallenge\Domain\Shop\Service\CartItemExpirationService;
use Planet\InterviewChallenge\Service\DateTimeFactory;

class CartItemExpirationServiceTest extends TestCase
{
    /**
     * @dataProvider generateExpirationDataProvider
     */
    public function testGenerateExpiration(
        int $mode,
        ?int $modifier,
        int $baseTimestamp,
        int $expectedOutput
    ): void {

        $dateTimeFactoryMock = $this->getMockBuilder(DateTimeFactory::class)
            ->getMock();

        $dateTimeFactoryMock
            ->method('now')
            ->willReturn((new DateTimeImmutable())->setTimestamp($baseTimestamp));

        $subject = new CartItemExpirationService($dateTimeFactoryMock);

        $output = $subject->generateExpiration($mode, $modifier);

        $this->assertEquals($expectedOutput, $output);
    }

    public function generateExpirationDataProvider(): array
    {
        $baseDateTime = new DateTimeImmutable('2020-01-01 00:00:00');

        return [
            'MODE_NO_LIMIT' => [
                'mode' => CartItemExpirationService::MODE_NO_LIMIT,
                'modifier' => null,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => -2,
            ],
            'MODE_HOUR' => [
                'mode' => CartItemExpirationService::MODE_HOUR,
                'modifier' => null,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => $baseDateTime->modify('+1 hour')->getTimestamp()
            ],
            'MODE_MINUTE 5' => [
                'mode' => CartItemExpirationService::MODE_MINUTE,
                'modifier' => 5,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => $baseDateTime->modify('+5 minutes')->getTimestamp()
            ],
            'MODE_MINUTE 15' => [
                'mode' => CartItemExpirationService::MODE_MINUTE,
                'modifier' => 15,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => $baseDateTime->modify('+15 minutes')->getTimestamp()
            ],
            'MODE_SECONDS 5' => [
                'mode' => CartItemExpirationService::MODE_SECONDS,
                'modifier' => 5,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => $baseDateTime->modify('+5 seconds')->getTimestamp()
            ],
            'MODE_SECONDS 15' => [
                'mode' => CartItemExpirationService::MODE_SECONDS,
                'modifier' => 15,
                'baseTimestamp' => $baseDateTime->getTimestamp(),
                'expectedOutput' => $baseDateTime->modify('+15 seconds')->getTimestamp()
            ],
        ];
    }

    public function testGenerateExpirationFailOnInvalidMode(): void
    {
        $subject = new CartItemExpirationService(new DateTimeFactory());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid mode: -1');

        $subject->generateExpiration(-1);
    }

    /**
     * @dataProvider isAvailableDataProvider
     *
     * @param bool[] $expected
     */
    public function testIsAvailable(
        ?int $modifier,
        array $expected
    ): void {
        $now = new DateTimeImmutable();

        $dateTimeFactoryMock = $this->getMockBuilder(DateTimeFactory::class)
            ->getMock();

        $dateTimeFactoryNowMock = $dateTimeFactoryMock->method('now');
        $dateTimeFactoryNowMock->willReturn($now);

        $mode = $modifier ? CartItemExpirationService::MODE_SECONDS : CartItemExpirationService::MODE_NO_LIMIT;

        $subject = new CartItemExpirationService($dateTimeFactoryMock);

        $expiration = $subject->generateExpiration($mode, $modifier);

        $cartItem = new CartItem(123, $expiration);

        $tests = count($expected);
        $startTimestamp = $now->getTimestamp();
        $increment = ($expiration -  $startTimestamp) / $tests;

        foreach ($expected as $expectedResult) {
            $now = $now->modify(sprintf('+%d seconds', $increment));
            $dateTimeFactoryNowMock->willReturn($now);

            $result = $subject->isAvailable($cartItem);
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function isAvailableDataProvider(): array
    {
        return [
            'Non Expiring Item' => [
                'modifier' => null,
                'expected' => [ true ],
            ],
            'Item Expires in 6 seconds' => [
                'modifier' => 6,
                'expected' => [ false, true ],
            ]
        ];
    }
}
