<?php

namespace Planet\InterviewChallenge\Shop;

use Planet\InterviewChallenge\App;

class CartItem
{
		const MODE_NO_LIMIT = 0;

		const MODE_HOUR = 1;

		const MODE_MINUTE = 10;

		const MODE_SECONDS = 1000;

		private int $expires;

		private int $price;

		public function __construct(int $price, $mode, $modifier = null)
		{
		    switch ($mode) {
		    case self::MODE_NO_LIMIT:
		        $this->expires = -2;
		        break;
		    case self::MODE_HOUR:
		        $this->expires = strtotime('+1 hour');
                break;
		    case self::MODE_MINUTE:
		        $this->expires = strtotime('+' . $modifier . ' minutes');
		        break;
		    case self::MODE_SECONDS:
		        $this->expires = strtotime('+' . $modifier . ' seconds');
		        break;
		    }
		    $this->price = $price;
		}

		public function is_available(): ?bool
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
		    return '{"price":' . $this->price . ',"expires":'.$this->expires.'}';
		}

		public function display(): string
		{
		    App::smarty()->assign('price', $this->price);
		    App::smarty()->assign('expires', $this->expires);

		    return App::smarty()->fetch('shop/CartItem.tpl');
		}
}
