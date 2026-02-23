<?php

namespace LandingCore\Core;

use CodeIgniter\Controller;
use LandingCore\Services\SEOService;

class BaseController extends Controller
{
    protected SEOService $seo;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->seo = new SEOService();
    }

    protected function render(string $view, array $data = [], string $layout = 'front')
    {
        $data['seo'] = $this->seo;
        $data['content'] = view($view, $data);

        return view("layouts/{$layout}", $data);
    }
}