<?php

declare(strict_types=1);

namespace Iquety\Presentation\Engine\Latte;

use Iquety\Presentation\Engine\Latte\Tags\CanNode;
use Iquety\Presentation\Engine\Latte\Tags\CannotNode;
use Iquety\Presentation\Engine\Latte\Tags\NCanelseNode;
use Iquety\Presentation\Engine\Latte\Tags\NCannotelseNode;
use Latte\Extension as LatteExtension;

/** @see vendor/latte/latte/src/Latte/Essential/CoreExtension.php */
class Extension extends LatteExtension
{
	/**
	 * Retorna a lista de tags fornecidas por esta extensão.
	 * @return array<string, callable> Mapa: 'nome-da-tag' => funcao-de-parsing
	 */
	public function getTags(): array
	{
		return [
			'can'           => CanNode::create(...),
			'cannot'        => CannotNode::create(...),
			'n:canelse'    => NCanelseNode::create(...),
			'n:cannotelse' => NCannotelseNode::create(...),
		];
	}

	public function getPasses(): array
	{
		return [
			'nCanelse'    => NCanelseNode::processPass(...),
			'nCannotelse' => NCannotelseNode::processPass(...),
		];
	}
}
