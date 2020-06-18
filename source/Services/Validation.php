<?php

namespace Source\Services;

class Validation {

    public static function requiredString($value)
    {
        return (!empty($value) && is_string($value));
    }

    public static function requiredInt($value)
    {
        return (!empty($value) && filter_var($value, FILTER_VALIDATE_INT));
    }

    public static function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}