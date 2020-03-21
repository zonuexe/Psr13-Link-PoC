# PSR-13 Link PoC

[PSR-13: Link definition interfaces - PHP-FIG](https://www.php-fig.org/psr/psr-13/) is probably the least known PHP-FIG's recommendation, but its specifications and implementation are flawed.

## WebLink and URI Template

The specification is very distorted because `Psr\Link\LinkInterface` requires the `isTemplated` method.  That specification was introduced in [RFC 6570](https://tools.ietf.org/html/rfc6570).

[PSR-13 1.4 Link Templates](https://www.php-fig.org/psr/psr-13/#14-link-templates)

> Some hypermedia formats support templated links while others do not, and may have a special way to denote that a link is a template. A Serializer for a format that does not support URI Templates MUST ignore any templated Links it encounters.

It is inevitable that unassembled template URIs are left behind. Because it is not the expected URI.

However, [`TemplatedHrefTrait::hrefIsTemplated()`](https://github.com/php-fig/link-util/blob/1.1.0/src/TemplatedHrefTrait.php) implementation of [fig/link-util](https://github.com/php-fig/link-util) that try to detect it are a bad idea.

```php
    private function hrefIsTemplated($href)
    {
        return strpos($href, '{') !== false ||strpos($href, '}') !== false;
    }
```


This code goes against secure coding best practices. Never search for a string to determine if it is a template or not.  Instead, define a separate Link class for fixed URIs and template URIs.

`"{"` and `"}"` are not delimiter in [RFC 3986](https://tools.ietf.org/html/rfc3986) URI Generic Syntax.

To create a FIG's `Link` object from user input like `$_SERVER['REQUEST_URI']` you need to explicitly escape.

```php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Do not use https://www.php.net/urlencode
$link = new Link('current', strtr($uri, ['{' => '%7B', '}' => '%7D']));
```

The same implementation has been [copied to Symfony](https://github.com/symfony/symfony/pull/33122).

## Why URI Template?

I noticed this problem about a year ago, but never came up with a use case.
