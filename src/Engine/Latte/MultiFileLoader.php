<?php declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte;

use Latte;
use Latte\Loader;

use function array_pop, end, explode, file_get_contents, implode, is_file, preg_match, str_starts_with, strtr;
use const DIRECTORY_SEPARATOR;


/**
 * Loads templates from filesystem.
 */
class MultiFileLoader implements Loader
{
	protected ?string $baseDir = null;

	/** @var array<string> */
	protected array $viewPaths = [];

	public function __construct(array $pathList)
	{
		foreach ($pathList as $viewPath) {
			$this->viewPaths[] = $this->normalizePath("$viewPath/");
		}
	}


	/**
	 * Returns template source code.
	 */
	public function getContent(string $fileName): string
	{
		foreach ($this->viewPaths as $baseDir) {
			$content = $this->searchTemplate($baseDir, $fileName);
			if ($content !== null) {
				return $content;
			}
		}
		
		throw new Latte\TemplateNotFoundException("Missing template file '$fileName'.");
	}

	private function searchTemplate(string $baseDir, string $fileName): ?string
	{
		$file = $baseDir . $fileName;

		if ($baseDir && !str_starts_with($this->normalizePath($file), $baseDir)) {
			throw new Latte\RuntimeException("Template '$file' is not within the allowed path '{$baseDir}'.");

		} elseif (!is_file($file)) {
			return null;
		}

		$content = file_get_contents($file);
		if ($content === false) {
			throw new Latte\RuntimeException("Unable to read file '$file'.");
		}

		return $content;
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $file, string $referringFile): string
	{
		if ($this->baseDir || !preg_match('#/|\\\|[a-z]:|phar:#iA', $file)) {
			$file = $this->normalizePath($referringFile . '/../' . $file);
		}

		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $file): string
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	protected static function normalizePath(string $path): string
	{
		preg_match('#^([a-z]:|phar://.+?/)?(.*)#i', $path, $m) ?: throw new \LogicException;
		$res = [];
		foreach (explode('/', strtr($m[2], '\\', '/')) as $part) {
			if ($part === '..' && $res && end($res) !== '..' && end($res) !== '') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}

		return $m[1] . implode(DIRECTORY_SEPARATOR, $res);
	}
}
