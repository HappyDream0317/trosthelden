<?php


namespace App\Helpers;

class MournForHelper
{
    // answer_code => reason
    private const RELATIONS = [
        '1,01,01' => [
            '2.01' => 'Ehemann',
            '2.02' => 'Ehefrau',
            '2.03' => 'Ehepartner (d)',
        ],
        '1,01,02' => [
            '2.01' => 'Lebenspartner',
            '2.02' => 'Lebenspartnerin',
            '2.03' => 'Lebenspartner (d)',
        ],
        '1,01,03' => [
            '2.01' => 'ehemaliger Partner',
            '2.02' => 'ehemalige Partnerin',
            '2.03' => 'ehemaliger Partner (d)',
        ],
        '1,01,04' => [
            '2.01' => 'inoffizielle/heimliche Beziehung (m)',
            '2.02' => 'inoffizielle/heimliche Beziehung (w)',
            '2.03' => 'inoffizielle/heimliche Beziehung (d)',
        ],
        '1,02,01' => [
            '2.01' => 'Sohn',
            '2.02' => 'Tochter',
            '2.03' => 'Kind (d)',
        ],
        '1,02,02' => [
            '2.01' => 'Adoptivsohn',
            '2.02' => 'Adoptivtochter',
            '2.03' => 'Adoptivkind (d)',
        ],
        '1,02,03' => [
            '2.01' => 'Pflegesohn',
            '2.02' => 'Pflegetochter',
            '2.03' => 'Pflegekind (d)',
        ],
        '1,02,04' => [
            '2.01' => 'Stief-/Patchwork-Sohn ',
            '2.02' => 'Stief-/Patchwork-Tochter',
            '2.03' => 'Stief-/Patchwork-Kind (d)',
        ],
        '1,03,01' => [
            '2.01' => 'Vater',
            '2.02' => 'Mutter',
            '2.03' => 'Elternteil (d)',
        ],
        '1,03,02' => [
            '2.01' => 'Adoptivvater',
            '2.02' => 'Adoptivmutter',
            '2.03' => 'Adoptivelternteil (d)',
        ],
        '1,03,03' => [
            '2.01' => 'Pflegevater',
            '2.02' => 'Pflegemutter',
            '2.03' => 'Pflegeelternteil (d)',
        ],
        '1,03,04' => [
            '2.01' => 'Stief-/Patchwork-Vater',
            '2.02' => 'Stief-/Patchwork-Mutter',
            '2.03' => 'Stief-/Patchwork-Elternteil (d)',
        ],
        '1,04,01' => [
            '2.01' => 'Bruder',
            '2.02' => 'Schwester',
            '2.03' => 'Geschwister (d)',
        ],
        '1,04,02' => [
            '2.01' => 'Zwillingsbruder',
            '2.02' => 'Zwillingsschwester',
            '2.03' => 'Zwillingsgeschwister (d)',
        ],
        '1,04,03' => [
            '2.01' => 'Mehrlingsbruder',
            '2.02' => 'Mehrlingsschwester',
            '2.03' => 'Mehrlingsgeschwister (d)',
        ],
        '1,04,04' => [
            '2.01' => 'Adoptivbruder',
            '2.02' => 'Adoptivschwester',
            '2.03' => 'Adoptivgeschwister (d)',
        ],
        '1,04,05' => [
            '2.01' => 'Pflegebruder',
            '2.02' => 'Pflegeschwester',
            '2.03' => 'Pflegegeschwister (d)',
        ],
        '1,04,06' => [
            '2.01' => 'Stief-/Patchwork-Bruder',
            '2.02' => 'Stief-/Patchwork-Schwester',
            '2.03' => 'Stief-/Patchwork-Geschwister (d)',
        ],
        '1,05,01' => [
            '2.01' => 'Großvater',
            '2.02' => 'Großmutter',
            '2.03' => 'Großelternteil (d)',
        ],
        '1,05,02' => [
            '2.01' => 'Enkelsohn',
            '2.02' => 'Enkeltochter',
            '2.03' => 'Enkelkind (d)',
        ],
        '1,05,03' => [
            '2.01' => 'Onkel',
            '2.02' => 'Tante',
            '2.03' => 'Onkel/Tante (d)',
        ],
        '1,05,04' => [
            '2.01' => 'Patenonkel',
            '2.02' => 'Patentante',
            '2.03' => 'Patenonkel/-tante (d)',
        ],
        '1,05,05' => [
            '2.01' => 'Neffe',
            '2.02' => 'Nichte',
            '2.03' => 'Neffe/Nichte (d)',
        ],
        '1,05,06' => [
            '2.01' => 'Patensohn',
            '2.02' => 'Patentochter',
            '2.03' => 'Patenkind (d)',
        ],
        '1,05,07' => [
            '2.01' => 'Cousin',
            '2.02' => 'Cousine',
            '2.03' => 'Cousin/Cousine (d)',
        ],
        '1,05,08' => [
            '2.01' => 'Schwiegervater',
            '2.02' => 'Schwiegermutter',
            '2.03' => 'Schwiegerelternteil (d)',
        ],
        '1,05,09' => [
            '2.01' => 'Schwiegersohn',
            '2.02' => 'Schwiegertochter',
            '2.03' => 'Schwiegerkind (d)',
        ],
        '1,05,10' => [
            '2.01' => 'Schwager',
            '2.02' => 'Schwägerin',
            '2.03' => 'Schwager/Schwägerin (d)',
        ],
        '1,06,01' => [
            '2.01' => 'Freund',
            '2.02' => 'Freundin',
            '2.03' => 'Freund/in (d)',
        ],
        '1,06,02' => [
            '2.01' => 'Nachbar',
            '2.02' => 'Nachbarin',
            '2.03' => 'Nachbar/in (d)',
        ],
        '1,06,03' => [
            '2.01' => 'Arbeitskollege',
            '2.02' => 'Arbeitskollegin',
            '2.03' => 'Arbeitskollege/in (d)',
        ],
    ];

    /**
     * @param $code string
     * @return null|string
    */
    public static function get($morningCode, $sexCode)
    {
        if (!$morningCode || !$sexCode) {
            return null;
        }

        if (array_key_exists($morningCode, self::RELATIONS)
             && array_key_exists($sexCode, self::RELATIONS[$morningCode])
        ) {
            return self::RELATIONS[$morningCode][$sexCode];
        }

        return null;
    }
}
