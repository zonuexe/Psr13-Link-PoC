<?php

namespace zonuexe\WebLink;

use Psr\Link\EvolvableLinkInterface;

final class SafeLinkFactory implements LinkFactoryInterface
{
    public function create(array $rels, string $href): EvolvableLinkInterface
    {
        return new ConcreteLink($href, $rels, []);
    }
}
