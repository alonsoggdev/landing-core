<?php

namespace LandingCore\Services;

use Config\Services;
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

        if ($this->request->getMethod() !== 'post') {
            return new FormResult(false, ['Invalid request method.']);
        }

        $validation = Services::validation();
        $validation->setRules($rules);

        $postData = $this->request->getPost();

        if (! $validation->run($postData)) {
            return new FormResult(
                false,
                $validation->getErrors()
            );
        }

        $cleanData = $validation->getValidated();

        // Notify owner
        if (($options['notify_owner'] ?? false) === true) {

            $ownerEmail = $options['owner_email'] ?? env('FORM_OWNER_EMAIL');

            if ($ownerEmail) {

                $email = Services::email();

                $email->setTo($ownerEmail);
                $email->setSubject("New submission from {$formName} form");

                // Vista del email
                $body = view("emails/{$formName}_owner_notification", [
                    'data' => $cleanData,
                    'formName' => $formName
                ]);

                $email->setMessage($body);

                $email->send();
            }
        }

        return new FormResult(true, [], $cleanData);
    }
}