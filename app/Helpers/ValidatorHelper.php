<?php


namespace App\Helpers;

class ValidatorHelper
{
    public static function password($password)
    {
        $valid = false;
        //letters A-Z or a-z and numbers

        if ((preg_match('/[A-Z]/', $password) || preg_match('/[a-z]/', $password)) && preg_match('/\d/', $password)) {
            $valid = true;
        }

        return [
            'Bitte wähle ein sicheres Passwort aus. Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Buchstaben sowie eine Zahl beinhalten',
            $valid
        ];
    }
}
