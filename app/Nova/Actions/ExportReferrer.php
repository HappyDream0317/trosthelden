<?php

namespace App\Nova\Actions;

use App\User;
use App\Referrer;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportReferrer extends DownloadExcel
{

    /**
     * The text in the first row of the table
     *
     * @return string
     */
    public function name(): string
    {
        return "Referrers (.csv)";
    }
    
    /**
     * The text in the first row of the table
     *
     * @return string
     */    
    protected function getFilename(): string
    {
        return "Referrers.csv";
    }


    /**
     * The text in the first row of the table
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nickname',
            'Name',
            'Lastname',
            'E-Mail',
            'Status',
            'Referrer',
            'Campaign',
            'Medium',
            'Source',
            'Content',
            'Referring Domain',
            'Keyword',
        ];
    }

    /**
     * The text in the first row of the table
     * @param  Referrer  $referrer
     * @return array
     */
    public function map($referrer): array
    {
        $user = User::find($referrer->user_id);
        return [
            $user->id,
            $user->nickname,
            $user->firstname,
            $user->lastname,
            $user->email,
            ($user->is_premium) ? 'premium' : 'non-premium',
            $referrer->referrer,
            $referrer->campaign,
            $referrer->medium,
            $referrer->source,
            $referrer->content,
            $referrer->referring_domain,
            $referrer->keyword,
        ];
    }
}
