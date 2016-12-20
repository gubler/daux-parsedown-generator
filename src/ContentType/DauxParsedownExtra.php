<?php

namespace Todaymade\Daux\Format\HTML\ContentTypes;

use Todaymade\Daux\Config;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception\LinkNotFoundException;
use Todaymade\Daux\Tree\Entry;

/**
 * Class DauxParsedownExtra
 *
 * @package Todaymade\Daux\Format\HTML\ContentTypes
 */
class DauxParsedownExtra extends \ParsedownExtra
{
    /**
     * @var Config
     */
    protected $daux;

    /**
     * DauxParsedownExtra constructor.
     *
     * @param Config $daux
     */
    public function __construct(Config $daux)
    {
        $this->daux = $daux;

        parent::__construct();
    }

    /**
     * @param $Excerpt
     *
     * @return array
     */
    protected function inlineLink($Excerpt)
    {
        $Link = parent::inlineLink($Excerpt);

        $remainder = substr($Excerpt['text'], $Link['extent']);

        if (preg_match('/^[ ]*{('.$this->regexAttribute.'+)}/', $remainder, $matches))
        {
            $Link['element']['attributes'] += $this->parseAttributeData($matches[1]);

            $Link['extent'] += strlen($matches[0]);
        }

        $Link = $this->parseUrl($Link);

        return $Link;
    }


    /**
     * @param array $link Parsedown $Link array
     *
     * @return array
     * @throws LinkNotFoundException
     */
    public function parseUrl(array $link)
    {
        $url = $link['element']['attributes']['href'];

        // empty urls and anchors should
        // not go through the url resolver
        if (!$this->isValidUrl($url)) {
            return $link;
        }

        // Absolute urls, shouldn't either
        if ($this->isExternalUrl($url)) {
            return $this->addClass($link, 'external');
        }

        // if there's a hash component in the url, ensure we
        // don't put that part through the resolver.
        $urlAndHash = explode('#', $url);
        $url = $urlAndHash[0];

        try {
            $file = $this->resolveInternalFile($url);
            $url = DauxHelper::getRelativePath($this->daux->getCurrentPage()->getUrl(), $file->getUrl());
        } catch (LinkNotFoundException $e) {
            if ($this->daux->isStatic()) {
                throw $e;
            }

            $link = $this->addClass($link, 'broken');
        }

        if (isset($urlAndHash[1])) {
            $url .= '#' . $urlAndHash[1];
        }

        $link['element']['attributes']['href'] = $url;

        return $link;
    }

    /**
     * @param string $url
     * @return Entry
     * @throws LinkNotFoundException
     */
    protected function resolveInternalFile($url)
    {
        $triedAbsolute = false;

        // Legacy absolute paths could start with
        // "!" In this case we will try to find
        // the file starting at the root
        if ($url[0] == '!' || $url[0] == '/') {
            $url = ltrim($url, '!/');

            if ($file = DauxHelper::getFile($this->daux['tree'], $url)) {
                return $file;
            }

            $triedAbsolute = true;
        }

        // Seems it's not an absolute path or not found,
        // so we'll continue with the current folder
        if ($file = DauxHelper::getFile($this->daux->getCurrentPage()->getParent(), $url)) {
            return $file;
        }

        // If we didn't already try it, we'll
        // do a pass starting at the root
        if (!$triedAbsolute && $file = DauxHelper::getFile($this->daux['tree'], $url)) {
            return $file;
        }

        throw new LinkNotFoundException("Could not locate file '$url'");
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isValidUrl($url)
    {
        return !empty($url) && $url[0] != '#';
    }

    /**
     * @param string $url
     *
     * @return int
     */
    protected function isExternalUrl($url)
    {
        return preg_match('|^(?:[a-z]+:)?//|', $url);
    }

    /**
     * @param array  $link  Parsdown $Link array
     * @param string $class class to add to link
     *
     * @return array
     */
    protected function addClass(array $link, $class)
    {
        if (!empty($link['element']['attributes']['class'])) {
            $classes = explode(' ', $link['element']['attributes']['class']);

            if (in_array($class, $classes)) {
                $link['element']['attributes']['class'] = implode(' ', $classes);
            }

            $classes[] = $class;
            $link['element']['attributes']['class'] = implode(' ', $classes);

            return $link;
        }

        $link['element']['attributes']['class'] = $class;

        return $link;

    }
}
