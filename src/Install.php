<?php

namespace wahyuief\csrfman;

class Install
{
    const WEBMAN_PLUGIN = true;

    protected static $pathRelation = array(
        'config/plugin/wahyuief/csrfman' => 'config/plugin/wahyuief/csrfman',
    );

    public static function install()
    {
        static::installByRelation();
    }

    public static function uninstall()
    {
        self::uninstallByRelation();
    }

    public static function installByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path() . '/' . substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            copy_dir(__DIR__ . "/$source", base_path() . "/$dest");
        }
    }

    public static function uninstallByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path() . "/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            remove_dir($path);
        }
    }

}
