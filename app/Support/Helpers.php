<?php

if (! function_exists('module_path')) {
    function module_path(string $module, string $path = ''): string
    {
        $basePath = base_path('modules/'.lcfirst($module).'/src');

        return $path ? $basePath.'/'.$path : $basePath;
    }
}
