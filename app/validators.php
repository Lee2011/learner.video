<?php

$validator->extend(
    'valid_username',
    function($attribute, $value, $parameters) {
        return ! in_array($value, config('config.forbidden_usernames', []));
    },
    trans('validators.username')
);