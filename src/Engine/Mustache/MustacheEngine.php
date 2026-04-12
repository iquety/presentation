<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Mustache;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Mustache\Engine;
use Mustache\Loader\CascadingLoader;
use Mustache\Loader\FilesystemLoader;

class MustacheEngine implements TemplateEngine
{
    private ?Engine $instance = null;

    private string $cachePath = '';
    
    private array $defaultData = [];

    private array $viewPaths = [];

    public function addDefaultData(array $data): void
    {
        $this->defaultData =+ $data;
    }

    public function addViewPath(string $viewPath): void
    {
        $this->viewPaths[] = $viewPath;
    }

    public function setCachePath(string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

    /** @return Engine */
    public function getEngine(): mixed
    {
        return $this->engine();
    }

    private function engine(): Engine
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        $loaderList = [];

        foreach ($this->viewPaths as $viewPath) {
            $loaderList[] = new FilesystemLoader($viewPath, ['extension' => '.ms']);
        }

        $settings = [
            'entity_flags' => ENT_QUOTES,
            'loader'       => new CascadingLoader($loaderList),
        ];

        if ($this->cachePath !== '') {
            $settings['cache'] = $this->cachePath;
            $settings['cache_file_mode'] = 0666; // Optional: Set file permissions
        }

        $mustache = new Engine($settings);

        $this->instance = $mustache;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.ms';
        $variables = array_merge($this->defaultData, $data);

        return $this->engine()->render($template, $variables);
    }
}

