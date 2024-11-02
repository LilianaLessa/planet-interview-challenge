<?php

namespace Planet\InterviewChallenge\Domain\Shop;

class CartItem
{
    const MODE_NO_LIMIT = 0;

    const MODE_HOUR = 1;

    const MODE_MINUTE = 10;

    const MODE_SECONDS = 1000;

    private int $expires;

    private int $price;

    public function __construct(int $price, int $mode, ?int $modifier = null)
    {
        $this->expires = $this->generateExpiration($mode, $modifier);
        $this->price = $price;
    }

    public function isAvailable(): bool
    {
        return $this->expires <= time();
    }

   /**
    * Returns the state representation of the object.
    *
    * @param int $format Constant from the class CartItem
    * @return string|object State representation of the class.
    */
    public function getState(): string
    {
        return json_encode(
            [
                "price" => $this->price,
                "expires" => $this->expires,
            ],
            JSON_FORCE_OBJECT
        );
    }

    private function generateExpiration(int $mode, ?int $modifier = null): int
    {
        switch ($mode) {
            case self::MODE_NO_LIMIT:
                return -2;
            case self::MODE_HOUR:
                return strtotime('+1 hour');
            case self::MODE_MINUTE:
                return strtotime('+' . $modifier . ' minutes');
            case self::MODE_SECONDS:
                return strtotime('+' . $modifier . ' seconds');
        }
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }
}
