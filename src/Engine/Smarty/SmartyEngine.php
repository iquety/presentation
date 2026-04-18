<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Smarty\Cacheresource\File;
use Smarty\CompilerException;
use Smarty\Exception;
use Smarty\Smarty;

class SmartyEngine implements TemplateEngine
{
    private ?Smarty $engine = null;

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

        if ($cachePath === '') {
            throw new PathException('The cache path was not specified.');
        }

        $smarty = new Smarty();

        $smarty->debugging = $this->debugMode;

        foreach ($viewPathList as $viewPath) {
            $smarty->addTemplateDir($viewPath);
        }

        $smarty->setCacheResource(new File());
        $smarty->setCacheDir($cachePath . DIRECTORY_SEPARATOR . 'cached');
        $smarty->setCompileDir($cachePath . DIRECTORY_SEPARATOR . 'compiled');

        if ($this->debugMode === true) {
            $smarty->setCaching($smarty::CACHING_OFF);
            $smarty->setForceCompile(true);
        }

        $smarty->addExtension(new Extension());

        $this->engine = $smarty;

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

        $template = str_replace('.', '/', $template) . '.tpl';
        $variables = array_merge($defaultData, $data);

        if ($this->debugMode === true) {
            $this->engine->clearCache($template);
        }

        try {
            return $this->engine->fetch($template, $variables);
        } catch (CompilerException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw new ViewException(sprintf('Unable to find template "%s"', $template), 0, $exception);
        }
    }
}
