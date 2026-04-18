<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Mustache;

use Closure;
use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Mustache\Engine;
use Mustache\Exception\UnknownTemplateException;
use Mustache\Loader\CascadingLoader;
use Mustache\Loader\FilesystemLoader;

class MustacheEngine implements TemplateEngine
{
    private ?Engine $engine = null;

    private bool $debugMode = false;

    public function enableDebug(): void
    {
        $this->debugMode = true;
    }
    
    /**
     * @param array<string> $viewPathList
     * @return Environment
     * @throws PathException
     * @return TemplateEngine
     */
    public function bootEngine(array $viewPathList, string $cachePath): TemplateEngine
    {
        if ($viewPathList === []) {
            throw new PathException('No template paths were specified.');
        }

        $loaderList = [];

        foreach ($viewPathList as $viewPath) {
            $loaderList[] = new FilesystemLoader($viewPath, ['extension' => '.ms']);
        }

        $settings = [
            'entity_flags' => ENT_QUOTES,
            'loader'       => new CascadingLoader($loaderList),
        ];

        if ($cachePath !== '') {
            $settings['cache'] = $cachePath;
            $settings['cache_file_mode'] = 0o644;
        }

        $mustache = new Engine($settings);

        $this->engine = $mustache;

        return $this;
    }

    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $defaultData
     * @throws EngineException
     * @throws ViewException
     */
    public function render(string $template, array $data = [], array $defaultData = []): string
    {
        if ($this->engine === null) {
            throw new EngineException('The engine was not booted.');
        }

        $template = str_replace('.', '/', $template) . '.ms';
        $variableList = $this->parsePermissionTags(array_merge($defaultData, $data));

        try {
            // todo: modificar o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->render($template, $variableList);
        } catch (UnknownTemplateException $exception) {
            throw new ViewException(sprintf('Unable to find template "%s"', $template), 0, $exception);
        }
    }

    /**
     * @param array<string,mixed> $variableList
     * @return array<string,mixed>
     */
    private function parsePermissionTags(array $variableList): array
    {
        $parsedVariables = [];

        foreach($variableList as $name => $value) {
            if(str_starts_with($name, 'permission') === true) {
                $this->makePermissionTags($name, $value, $parsedVariables);
                continue;
            }

            $parsedVariables[$name] = $value;
        }

        return $parsedVariables;
    }

    /** @param array<string,mixed> $variableList */
    private function makePermissionTags(string $tag, mixed $value, array & $variableList): void
    {
        $permissionTag = str_replace('permission-', '', $tag);
        $canTag = 'can-' . $permissionTag;
        $cannotTag = 'cannot-' . $permissionTag;

        unset($variableList[$tag]);

        $variableList[$canTag] = $this->makeCan($value);
        $variableList[$cannotTag] = $this->makeCannot($value);
    }

    private function makeCan(mixed $value): bool
    {
        return $value  === 'true' || $value === true || $value === '1' || $value === 1;
    }

    private function makeCannot(mixed $value): bool
    {
        return $value  === 'false' || $value === false || $value === '0' || $value === 0;
    }
}
