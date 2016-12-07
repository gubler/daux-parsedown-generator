<?php

namespace Todaymade\Daux\Format\HTML\ContentTypes;

use Todaymade\Daux\Config;
use Todaymade\Daux\ContentTypes\ContentType;
use Todaymade\Daux\Tree\Content;

/**
 * Class ParsedownContentType
 *
 * @package Todaymade\Daux\Format\HTML\ContentTypes
 */
class ParsedownContentType implements ContentType
{
    /** @var Config */
    protected $config;

    /** @var \ParsedownExtra */
    protected $converter;

    /**
     * ParsedownContentType constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->converter = new \ParsedownExtra();
    }

    /**
     * @return string[]
     */
    public function getExtensions()
    {
        return ['md', 'markdown'];
    }

    /**
     * @param string  $raw
     * @param Content $node
     * @return mixed|string
     */
    public function convert($raw, Content $node)
    {
        $this->config->setCurrentPage($node);

        return $this->converter->text($raw);
    }
}
