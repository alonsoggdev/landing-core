<?php

namespace LandingCore\CLI;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateFormCommand extends BaseCommand
{
    protected $group       = 'Landing';
    protected $name        = 'create-form';
    protected $description = 'Generates a complete form structure';

    public function run(array $params)
    {
        if (empty($params)) {
            CLI::error('Form name required.');
            return;
        }

        $formName = strtolower($params[0]);

        CLI::write("Creating form: {$formName}", 'green');
    }
}