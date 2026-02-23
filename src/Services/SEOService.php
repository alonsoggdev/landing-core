<?php

namespace LandingCore\Services;

class SEOService
{
    protected string $title = '';
    protected string $description = '';
    protected ?string $canonical = null;
    protected ?string $ogImage = null;
    protected string $ogType = 'website';
    protected array $meta = [];
    protected array $schemas = [];

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCanonical(string $url): void
    {
        $this->canonical = $url;
    }

    public function setOgImage(string $url): void
    {
        $this->ogImage = $url;
    }

    public function setOgType(string $type): void
    {
        $this->ogType = $type;
    }

    public function addMeta(string $name, string $content): void
    {
        $this->meta[$name] = $content;
    }

    public function addSchema(array $schema): void
    {
        $this->schemas[] = $schema;
    }

    protected function escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function render(): string
    {
        $title = $this->escape($this->title);
        $description = $this->escape($this->description);
        $canonical = $this->canonical ? $this->escape($this->canonical) : '';
        $ogImage = $this->ogImage ? $this->escape($this->ogImage) : '';
        $ogType = $this->escape($this->ogType);
        $schemaScripts = '';

        foreach ($this->schemas as $schema) {
            $jsonLD = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $schemaScripts = <<<HTML
                <script type="application/ld+json">
                    {$jsonLD}
                </script>
            HTML;
        }

        $extraMeta = '';

        foreach ($this->meta as $name => $content) {
            $content = $this->escape($content);
            $extraMeta .= "<meta name=\"{$name}\" content=\"{$content}\">\n";
        }

        $canonicalHTML = "";
        if ($canonical)
        {
            $canonicalHTML = <<<HTML
                <link rel="canonical" href="{$canonical}">
            HTML;
        }

        $ogImageHTML = "";
        if ($ogImage)
        {
            $ogImageHTML = <<<HTML
                <meta property="og:image" content="{$ogImage}">
            HTML;
        }

        return <<<HTML
            <title>{$title}</title>
            <meta name="description" content="{$description}">
            <meta property="og:title" content="{$title}">
            <meta property="og:description" content="{$description}">
            <meta property="og:type" content="{$ogType}">
            $canonicalHTML
            $ogImageHTML
            $extraMeta
            $schemaScripts
        HTML;
    }
}