<?php

namespace LandingCore\Core;

class BaseController
{
    protected array $meta = [];

    public function setMeta(string $key, string $value): void
    {
        $this->meta[$key] = $value;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}