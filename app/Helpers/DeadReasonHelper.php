<?php


namespace App\Helpers;

class DeadReasonHelper
{
    // answer_code => reason
    private const REASONS = [
        '4.01'       => 'Tod durch Altersprozess/-krankheit ',
        '4,02,01'    => 'Tod durch Krebs/Tumor',
        '4,02,02'    => 'Tod durch Herz-Kreislauf-Erkrankung',
        '4,02,03'    => 'Tod durch Atemwegserkrankung',
        '4,02,04'    => 'Tod durch Erkrankung des Verdauungssystems',
        '4,02,05'    => 'Tod infolge Verletzung/Vergiftung',
        '4,02,06'    => 'Tod durch körperliche Erkrankung',
        '4,02,06,01' => 'Tod durch körperliche Erkrankung',
        '4,02,07'    => 'Tod durch körperliche Erkrankung',
        '4,03,01'    => 'Tod infolge Alkoholsucht',
        '4,03,02'    => 'Tod infolge Arzneimittelsucht',
        '4,03,03'    => 'Tod infolge harter Drogen',
        '4,03,04'    => 'Tod infolge einer Sucht',
        '4,03,05'    => 'Tod infolge einer Sucht',
        '4,04,01'    => 'Tod durch häuslichen Unfall',
        '4,04,02'    => 'Tod durch Verkehrsunfall',
        '4,04,03'    => 'Tod durch Arbeits-/Schulunfall',
        '4,04,04'    => 'Tod durch Sport-/Spielunfall',
        '4,04,05'    => 'Tod durch Unfall',
        '4,04,06'    => 'Tod durch Unfall',
        '4.05'       => 'Tod durch Suizid',
        '4.06'       => 'Tod durch Tötungsdelikt',
        '4.07'       => 'Tod durch Schwangerschaftsabbruch',
        '4.08'       => 'Tod durch Fehl- oder Totgeburt',
        '4.09'       => 'Tod durch plötzlichen Kindstod',
        '4.10'       => 'Tod durch speziellen Umstand',
        '4.11'       => 'Tod auf ungeklärte Weise',
        '4.12'       => 'Tod durch unbekannte Ursache',
    ];

    /**
     * @param $code string
     * @return null|string
    */
    public static function get($code)
    {
        if (array_key_exists($code, self::REASONS)) {
            return self::REASONS[$code];
        }

        return null;
    }
}
