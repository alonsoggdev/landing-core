<?php

namespace LandingCore\DTO;

class FormResult
{
    public function __construct(
        public bool $success,
        public array $errors = [],
        public array $data = []
    ) {}
}