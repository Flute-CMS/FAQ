<?php

namespace Flute\Modules\FAQ\src\ServiceProviders\Extensions;

use Flute\Core\Admin\Builders\AdminSidebarBuilder;

class AdminExtension implements \Flute\Core\Contracts\ModuleExtensionInterface
{
    public function register(): void
    {
        $this->addSidebar();
    }

    private function addSidebar(): void
    {
        AdminSidebarBuilder::add('additional', [
            'title' => 'faq.admin.header',
            'icon' => 'ph-question',
            'permission' => 'admin.faq',
            'url' => '/admin/faq/list'
        ]);
    }
}