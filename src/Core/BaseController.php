<?php

namespace LandingCore\Core;

use CodeIgniter\Controller;
use LandingCore\Services\SEOService;
use LandingCore\Services\FormService;

class BaseController extends Controller
{
    protected SEOService $seo;
    protected FormService $form;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->seo = new SEOService();
        $this->form = new FormService($this->request);
    }

    protected function render(string $view, array $data = [], string $layout = 'front')
    {
        $data['seo'] = $this->seo;
        $data['content'] = view($view, $data);

        return view("layouts/{$layout}", $data);
    }
}