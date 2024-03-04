<?php

namespace App\Http\Middleware;

use App\Helpers\BlockRobotsHelper;
use Illuminate\Http\Request;
use Spatie\RobotsMiddleware\RobotsMiddleware;

class BlockRobotsMiddleware extends RobotsMiddleware
{
    /**
     * @return string|bool
     */
    protected function shouldIndex(Request $request): string
    {
        $blockList = BlockRobotsHelper::getBlockList();

        foreach ($blockList as $block) {

            if ($block !== '/') {
                $block = trim($block, '/');
            }

            if ($request->fullUrlIs($block) || $request->is($block)) {
                return 'noindex';
            }
        }

        return 'all';
    }

}
