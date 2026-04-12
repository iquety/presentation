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

    /** @var array<string,mixed> $defaultData */
    private array $defaultData = [];

    private string $cachePath = '';

    /** @var array<string> $viewPaths */
    private array $viewPaths = [];

    /** @param array<string,mixed> $data */
    public function addDefaultData(array $data): void
    {
        $this->defaultData = array_merge($this->defaultData, $data);
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

    /**
     * @param array<string,mixed> $data
     * @throws PathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.ms';
        $variables = array_merge($this->defaultData, $data);

        // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
        return $this->engine()->render($template, $variables);

        // todo: padronizar throw new PathException('View not found: ' . $template);
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
            $settings['cache_file_mode'] = 0o666; // Optional: Set file permissions
        }

        $mustache = new Engine($settings);

        $this->instance = $mustache;

        return $this->instance;
    }
}
