<?php

namespace LandingCore\Services;

use LandingCore\DTO\FormResult;

class FormService
{
    public function __construct(
        protected $request
    ) {}

    public function handle(
        string $formName,
        array $rules,
        array $options = []
    ): FormResult {
        return new FormResult(false);
    }
}