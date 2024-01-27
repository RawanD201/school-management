<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum BloodType: string
{
    use EnumToArray;

    case O_POS = 'O+';
    case O_NEG = 'O-';
    case A_POS = 'A+';
    case A_NEG = 'A-';
    case B_POS = 'B+';
    case B_NEG = 'B-';
    case AB_POS = 'AB+';
    case AB_NEG = 'AB-';

    /**
     * Return enum in associative array with respected key and values as value being ucfirst.
     */
    public static function display(): array
    {
        return [
            self::O_POS->value => __('O_POS'),
            self::O_NEG->value => __('O_NEG'),
            self::A_POS->value => __('A_POS'),
            self::A_NEG->value => __('A_NEG'),
            self::B_POS->value => __('B_POS'),
            self::B_NEG->value => __('B_NEG'),
            self::AB_POS->value => __('AB_POS'),
            self::AB_NEG->value => __('AB_NEG'),
        ];
    }


    /**
     * Return the translated text of given type.
     */
    public static function translate(string $type): string
    {
        return match ($type) {
            self::O_POS->value => __('O_POS'),
            self::O_NEG->value => __('O_NEG'),
            self::A_POS->value => __('A_POS'),
            self::A_NEG->value => __('A_NEG'),
            self::B_POS->value => __('B_POS'),
            self::B_NEG->value => __('B_NEG'),
            self::AB_POS->value => __('AB_POS'),
            self::AB_NEG->value => __('AB_NEG'),
        };
    }
}
