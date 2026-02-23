<?php

namespace LandingCore\Core;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    protected array $meta = [];
    protected array $viewData = [];

    public function setMeta(string $key, string $value): void
    {
        $this->meta[$key] = $value;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}