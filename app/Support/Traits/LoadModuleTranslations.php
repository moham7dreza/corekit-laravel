<?php

namespace App\Support\Traits;

trait LoadModuleTranslations
{
    protected function loadModuleTranslations(string $namespace): void
    {
        $moduleName = str_replace('\\Providers\\'.class_basename(static::class), '', static::class);
        $moduleName = class_basename($moduleName);

        $this->loadTranslationsFrom(
            module_path($moduleName, 'resources/lang'),
            $namespace
        );
    }
}
