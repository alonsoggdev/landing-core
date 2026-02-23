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

    protected function render(string $view, array $data = [])
    {
        $data['seo'] = $this->seo;

        return view($view, $data);
    }
}