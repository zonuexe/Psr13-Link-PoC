<?php

namespace zonuexe\WebLink;

use Fig\Link\Link;
use Psr\Link\EvolvableLinkInterface;

final class FigLinkFactory implements LinkFactoryInterface
{
    public function create(array $rels, string $href): EvolvableLinkInterface
    {
        $rel = array_pop($rels);

        $link = new Link($rel ?? '', $href);
        foreach ($rels as $rel) {
            $link = $link->withRel($rel);
        }

        return $link;
    }
}
