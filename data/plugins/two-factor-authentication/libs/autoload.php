<?php

/**
 * @param $className
 */
function __autoload($className)
{
    $ds = DIRECTORY_SEPARATOR;

    $fileName = '';
    $className = ltrim($className, '\\');
    $namespace = '';

    if ($lastNsPos = strripos($className, '\\'))
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', $ds, $namespace) . $ds . $className;
    }

    $fileName =  __DIR__ . $ds . $fileName . '.php';
    if (file_exists($fileName)) require_once $fileName;

}

spl_autoload_register('__autoload');