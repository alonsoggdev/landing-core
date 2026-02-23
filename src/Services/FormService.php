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
                $email->setFrom($ownerEmail);
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
        
        // Auto reply to user
        if (($options['auto_reply'] ?? false) === true) {

            $ownerEmail = $options['owner_email'] ?? env('FORM_OWNER_EMAIL');

            if (isset($cleanData['email']) && filter_var($cleanData['email'], FILTER_VALIDATE_EMAIL) && $ownerEmail) {

                $email = Services::email();

                $email->setFrom($ownerEmail);

                $email->setTo($cleanData['email']);
                $email->setSubject("We received your message");

                $body = view("emails/{$formName}_auto_reply", [
                    'data' => $cleanData,
                    'formName' => $formName
                ]);

                $email->setMessage($body);

                $email->send();
            }
        }

        // Store in database using model
        if (($options['store'] ?? false) === true) {

            $modelClass = 'App\\Models\\' . ucfirst($formName) . 'SubmissionModel';

            if (class_exists($modelClass)) {

                $model = new $modelClass();

                $model->insert($cleanData);
            }
        }

        return new FormResult(true, [], $cleanData);
    }
}