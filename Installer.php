<?php

namespace Flute\Modules\FAQ;

use Flute\Core\Database\Entities\Permission;
use Flute\Modules\FAQ\src\Widgets\FAQWidget;

class Installer extends \Flute\Core\Support\AbstractModuleInstaller
{
    public function install(\Flute\Core\Modules\ModuleInformation &$module): bool
    {
        $permission = rep(Permission::class)->findOne([
            'name' => 'admin.faq'
        ]);

        if (!$permission) {
            $permission = new Permission;
            $permission->name = 'admin.faq';
            $permission->desc = 'faq.perm_desc';

            transaction($permission)->run();
        }

        return true;
    }

    public function uninstall(\Flute\Core\Modules\ModuleInformation &$module): bool
    {
        $permission = rep(Permission::class)->findOne([
            'name' => 'admin.faq'
        ]);

        if ($permission) {
            transaction($permission, 'delete')->run();
        }

        widgets()->unregister(FAQWidget::class);

        return true;
    }
}