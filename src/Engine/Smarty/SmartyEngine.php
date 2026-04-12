<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Smarty;

use Iquety\Presentation\Engine\TemplateEngine;
use Iquety\Presentation\Engine\PathException;
use PhpParser\Node\Scalar\MagicConst\Dir;
use Smarty\Smarty;

class SmartyEngine implements TemplateEngine
{
    private ?Smarty $instance = null;

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

    /** @return Smarty */
    public function getEngine(): mixed
    {
        return $this->engine();
    }

    private function engine(): Smarty
    {
        if ($this->instance !== null) {
            return $this->instance;
        }

        $smarty = new Smarty();

        $smarty->debugging = true;
        
        if ($this->viewPaths === []) {
            throw new PathException('No view path was added.');
        }

        foreach ($this->viewPaths as $viewPath) {
            $smarty->addTemplateDir($viewPath);
        }
        
        if ($this->cachePath !== '') {
            $smarty->caching = true;
            $smarty->cache_lifetime = 120;

            $smarty->setCompileDir($this->cachePath . DIRECTORY_SEPARATOR . 'compiled');
            $smarty->setCacheDir($this->cachePath . DIRECTORY_SEPARATOR . 'cached');
        }

        // internamente o cache é modificado para @chmod($key, 0666 & ~umask());

        $this->instance = $smarty;

        return $this->instance;
    }

    /**
     * @param array<string,mixed> $data
     * @throws ViewPathException
     */
    public function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template) . '.tpl';
        $variables = array_merge($this->defaultData, $data);

        // internamente o cache é modificado para @chmod($key, 0666 & ~umask());
        return $this->engine()->fetch($template, $variables);

        // todo: padronizar throw new PathException('View not found: ' . $template);
    }
}

