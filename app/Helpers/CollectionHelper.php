<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CollectionHelper
{

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    static public int $perPage = 15;

    public static function paginate(Collection $results, $perPage = null, $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: self::$perPage;

        $total = $results->count();


        return self::paginator($results->forPage($page, $perPage)->values(), $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

}