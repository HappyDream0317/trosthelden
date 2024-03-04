<?php

namespace App\Models\Utils;

trait CustomizablePageSize
{
    protected $pageSizeLimit = 100;

    public function getPerPage()
    {
        $pageSize = request('page_size', $this->perPage);

        return min($pageSize, $this->pageSizeLimit);
    }
}
