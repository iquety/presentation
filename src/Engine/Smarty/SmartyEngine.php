<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty;

use Iquety\Presentation\Engine\EngineException;
use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use Iquety\Presentation\Engine\ViewException;
use Smarty\Exception;
use Smarty\Smarty;

class SmartyEngine implements TemplateEngine
{
    private ?Smarty $engine = null;

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

        $smarty = new Smarty();

        $smarty->debugging = true;

        foreach ($viewPathList as $viewPath) {
            $smarty->addTemplateDir($viewPath);
        }

        if ($cachePath !== '') {
            $smarty->caching = Smarty::CACHING_LIFETIME_SAVED;
            $smarty->cache_lifetime = 120;

            $smarty->setCompileDir($cachePath . DIRECTORY_SEPARATOR . 'compiled');
            $smarty->setCacheDir($cachePath . DIRECTORY_SEPARATOR . 'cached');
        }

        // internamente o cache é modificado para @chmod($key, 0666 & ~umask());

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

        try {
            // internamente o cache é modificado para @chmod($key, 0666 & ~umask());
            return $this->engine->fetch($template, $variables);
        } catch (Exception $exception) {
            throw new ViewException(sprintf('Unable to find template "%s"', $template), 0, $exception);
        }
    }
}
