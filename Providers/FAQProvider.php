<?php

namespace Flute\Modules\FAQ\Providers;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\FAQ\Widgets\FAQWidget;

class FAQProvider extends ModuleServiceProvider
{
    public array $extensions = [];

    public function boot(\DI\Container $container): void
    {
        $this->bootstrapModule();

        $this->loadViews('Resources/views', 'faq');
        $this->loadScss('Resources/assets/scss/faq.scss');

        $jsFile = template()->getTemplateAssets()->assetFunction(path('app/Modules/FAQ/Resources/assets/js/faq.js'));
        template()->prependToSection('footer', $jsFile);

        $this->loadWidget(FAQWidget::class, 'faq');
    }

    public function register(\DI\Container $container): void {}
}
