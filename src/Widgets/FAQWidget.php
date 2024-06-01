<?php

namespace Flute\Modules\FAQ\src\Widgets;

use Flute\Core\Widgets\AbstractWidget;
use Flute\Modules\FAQ\database\Entities\FaqItem;
use Flute\Modules\FAQ\src\Services\FaqService;

class FAQWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->setAssets([
            mm('FAQ', 'Resources/assets/styles/faq.scss'),
            mm('FAQ', 'Resources/assets/js/faq.js'),
        ]);
    }

    public function render(array $data = []): string
    {
        return render(mm('FAQ', 'Resources/views/faq'), [
            'faq' => $this->getFAQ(),
            'service' => app(FaqService::class)
        ]);
    }

    public function getName(): string
    {
        return 'FAQ widget';
    }

    public function isLazyLoad(): bool
    {
        return false;
    }

    protected function getFAQ()
    {
        return rep(FaqItem::class)->select()->load('blocks')->orderBy('created_at', 'desc')->fetchAll();
    }
}