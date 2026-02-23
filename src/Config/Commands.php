<?php

namespace LandingCore\Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    public $commands = [
        'create-form' => \LandingCore\CLI\CreateFormCommand::class,
    ];
}