<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Service;

use Smarty\Smarty;

class SmartyTemplateService
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->initSmarty();
    }

    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @throws \Smarty\Exception
     */
    private  function initSmarty(): void
    {
        $this->smarty = new Smarty();

        $this->smarty
            ->setTemplateDir([ __DIR__ . '/../tpl'])
            ->setCompileDir(__DIR__ . '/../../tmp/templates_c')
            ->setCacheDir(__DIR__ . '/../../tmp/cache');

        $this->smarty->registerPlugin('modifier', 'format_date', function ($timestamp, $format = 'Y-m-d') {
            return date($format, $timestamp);
        });
    }
}
