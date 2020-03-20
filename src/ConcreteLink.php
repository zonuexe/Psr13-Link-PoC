<?php

namespace zonuexe\WebLink;

use Psr\Link\EvolvableLinkInterface;

class ConcreteLink implements EvolvableLinkInterface
{
    private string $href;
    /** @var array<string,true> */
    private array $rels;
    /** @var array<string,string> */
    private array $attrs;

    /**
     * @param array<string> $rels
     * @param array<string,string> $attrs
     */
    final public function __construct(string $href, array $rels, array $attrs)
    {
        $this->href = $href;
        $this->rels = array_fill_keys($rels, true);
        $this->attrs = $attrs;
    }

    /**
     * Returns the target of the link.
     *
     * The target link must be one of:
     * - An absolute URI, as defined by RFC 5988.
     * - A relative URI, as defined by RFC 5988. The base of the relative link
     *     is assumed to be known based on context by the client.
     * - A URI template as defined by RFC 6570.
     *
     * If a URI template is returned, isTemplated() MUST return True.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Returns whether or not this is a templated link.
     *
     * @return bool
     *   True if this link object is templated, False otherwise.
     */
    public function isTemplated()
    {
        return false;
    }

    /**
     * Returns the relationship type(s) of the link.
     *
     * This method returns 0 or more relationship types for a link, expressed
     * as an array of strings.
     *
     * @return string[]
     */
    public function getRels()
    {
        return array_keys($this->rels);
    }

    /**
     * Returns a list of attributes that describe the target URI.
     *
     * @return array<string,string>
     *   A key-value list of attributes, where the key is a string and the value
     *  is either a PHP primitive or an array of PHP strings. If no values are
     *  found an empty array MUST be returned.
     */
    public function getAttributes()
    {
        return $this->attrs;
    }

    /**
     * Returns an instance with the specified href.
     *
     * @param string $href
     *   The href value to include.  It must be one of:
     *     - An absolute URI, as defined by RFC 5988.
     *     - A relative URI, as defined by RFC 5988. The base of the relative link
     *       is assumed to be known based on context by the client.
     *     - A URI template as defined by RFC 6570.
     *     - An object implementing __toString() that produces one of the above
     *       values.
     *
     * An implementing library SHOULD evaluate a passed object to a string
     * immediately rather than waiting for it to be returned later.
     *
     * @return static
     */
    public function withHref($href)
    {
        return new static($href, $this->getRels(), $this->attrs);
    }

    /**
     * Returns an instance with the specified relationship included.
     *
     * If the specified rel is already present, this method MUST return
     * normally without errors, but without adding the rel a second time.
     *
     * @param string $rel
     *   The relationship value to add.
     * @return static
     */
    public function withRel($rel)
    {
        $rels = $this->rels;
        $rels[$rel] = true;

        return new static($this->href, array_keys($rels), $this->attrs);
    }

    /**
     * Returns an instance with the specified relationship excluded.
     *
     * If the specified rel is already not present, this method MUST return
     * normally without errors.
     *
     * @param string $rel
     *   The relationship value to exclude.
     * @return static
     */
    public function withoutRel($rel)
    {
        $rels = $this->rels;
        unset($rels[$rel]);

        return new static($this->href, array_keys($rels), $this->attrs);
    }

    /**
     * Returns an instance with the specified attribute added.
     *
     * If the specified attribute is already present, it will be overwritten
     * with the new value.
     *
     * @param string $attribute
     *   The attribute to include.
     * @param string $value
     *   The value of the attribute to set.
     * @return static
     */
    public function withAttribute($attribute, $value)
    {
        $attrs = $this->attrs;
        $attrs[$attribute] = $value;

        return new static($this->href, array_keys($this->rels), $attrs);
    }


    /**
     * Returns an instance with the specified attribute excluded.
     *
     * If the specified attribute is not present, this method MUST return
     * normally without errors.
     *
     * @param string $attribute
     *   The attribute to remove.
     * @return static
     */
    public function withoutAttribute($attribute)
    {
        $attrs = $this->attrs;
        unset($attrs[$attribute]);

        return new static($this->href, array_keys($this->rels), $attrs);
    }
}
