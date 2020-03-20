<?php

namespace zonuexe\WebLink;

use Psr\Link\LinkInterface;
use function header;
use Symfony\Component\WebLink\HttpHeaderSerializer;

/**
 * @param array<LinkInterface> $links
 */
function emitLinkHeader(array $links): ?string
{
    $serializer = new HttpHeaderSerializer();
    $field = $serializer->serialize($links);

    header("Link: {$field}");

    return $field;
}
