<?php

declare(strict_types=1);

namespace Tests;

use Iquety\Presentation\Engine\Blade\BladeEngine;

trait HasBladeFixSpaces
{
    /**
     * O blade remove espaço antes e mantém depois da tag
     * Ex.: @can('my-permision) text @endcan = "text "
     */
    private function fixSpaces(string $result): string
    {
        $nodeList = explode('and', trim($result)); // separa antes e depois do "and"
        $nodeList = array_filter($nodeList); // remove valores vazios
        $nodeList = array_values($nodeList); // reordena o índice
        $nodeList = array_map(fn($value) => trim($value), $nodeList); // remove espaços

        if (count($nodeList) === 0) { // "and "
            return ' and '; // acrescenta antes
        }

        $newResult = [];

        // lado esquerdo "... say hi"
        if (count($nodeList) === 1 && str_ends_with($nodeList[0], 'hi') === true) {
            $newResult[] = trim($nodeList[0]);

            $newResult[] = 'and ';

            return implode(' ', $newResult);
        }

        // lado direito "... say hi"
        if (count($nodeList) === 1 && str_ends_with($nodeList[0], 'bye') === true) {
            $newResult[] = ' and'; 
            $newResult[] = trim($nodeList[0]);

            return implode(' ', $newResult);
        }

        // ambos lados "... say hi" e "... say by"
        if (count($nodeList) === 2) {
            $newResult[] = trim($nodeList[0]);
            $newResult[] = 'and';
            $newResult[] = trim($nodeList[1]);
        }

        return implode(' ', $newResult);
    }
}
