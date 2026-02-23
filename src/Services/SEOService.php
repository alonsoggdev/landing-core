<?php

namespace LandingCore\Services;

class SEOService
{
    protected string $title = '';
    protected string $description = '';
    protected array $meta = [];

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function addMeta(string $name, string $content): void
    {
        $this->meta[$name] = $content;
    }

    protected function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function render(): string
    {
        $title = $this->escape($this->title);
        $description = $this->escape($this->description);

        $metaTags = '';

        foreach ($this->meta as $name => $content) {
            $content = $this->escape($content);
            $metaTags .= "<meta name=\"{$name}\" content=\"{$content}\">\n";
        }

        return <<<HTML
            <title>
                {$title}
            </title>
            <meta name="description" content="{$description}">
            <meta property="og:title" content="{$title}">
            <meta property="og:description" content="{$description}">
            {$metaTags}
        HTML;
    }
}