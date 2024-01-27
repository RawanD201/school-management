<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum Gender: string
{
    use EnumToArray;

    case MALE = 'male';
    case FEMALE = 'female';

    /**
     * Return enum in associative array with respected key and values as value being ucfirst.
     */
    public static function display(): array
    {
        return [
            self::MALE->value => __('labels.gender.male'),
            self::FEMALE->value => __('labels.gender.female'),
        ];
    }


    /**
     * Return the translated text of given type.
     */
    public static function translate(string $type): string
    {
        return match ($type) {
            self::MALE->value => __('labels.gender.male'),
            self::FEMALE->value => __('labels.gender.female'),
        };
    }
}
