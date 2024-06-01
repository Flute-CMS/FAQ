<?php

namespace Flute\Modules\FAQ\src\ServiceProviders;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\FAQ\src\ServiceProviders\Extensions\AdminExtension;
use Flute\Modules\FAQ\src\Widgets\FAQWidget;

class FAQServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [
        AdminExtension::class
    ];

    public function boot(\DI\Container $container): void
    {
        $this->loadEntities();
        $this->loadRoutesFrom('app/Modules/FAQ/src/routes.php');

        widgets()->register(new FAQWidget);
    }

    public function register(\DI\Container $container): void
    {
        $this->loadTranslations();
    }
}