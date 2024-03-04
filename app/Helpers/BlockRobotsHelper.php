<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class BlockRobotsHelper
{

    /**
     * The URIs that should not be indexed.
     *
     * @var array
     */
    static protected $block = [
        '/login',
        '/imprint',
        '/privacy',
        '/checkout/*'
    ];

    public static function isNoIndex()
    {
        $blockList = self::getBlockList();

        foreach ($blockList as $block) {

            if ($block !== '/') {
                $block = trim($block, '/');
            }

            if (request()->fullUrlIs($block) || request()->is($block)) {
                return true;
            }
        }
        return false;
    }

    public static function getBlockList() {
        return self::$block;
    }
}