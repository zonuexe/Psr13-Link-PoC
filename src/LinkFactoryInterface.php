<?php

namespace zonuexe\WebLink;

use Psr\Link\EvolvableLinkInterface;

interface LinkFactoryInterface
{
    /**
     * @param array<string> $rels
     */
    public function create(array $rels, string $href): EvolvableLinkInterface;
}
