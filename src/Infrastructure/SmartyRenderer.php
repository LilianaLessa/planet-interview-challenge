<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Infrastructure;

use Smarty\Smarty;

class SmartyRenderer
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty) {
        $this->smarty = $smarty;
    }

    public function render(string $templateName, array $data = []): string
    {
        ob_start();
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display($templateName);
        $render = ob_get_contents();
        ob_end_clean();

        return $render;
    }
}
