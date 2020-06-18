<?php

namespace Source\Services;

class Validation {

    public static function requiredString(string $value)
    {
        return (!empty($value) && is_string($value));
    }

    public static function requiredInt(int $value)
    {
        return (!empty($value) && filter_var($value, FILTER_VALIDATE_INT));
    }

    public static function validEmail(string $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}