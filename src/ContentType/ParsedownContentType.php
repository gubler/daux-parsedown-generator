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

    /** @var DauxParsedownExtra */
    protected $converter;

    /**
     * ParsedownContentType constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->converter = new DauxParsedownExtra($config);
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

        $processed = $this->converter->text($raw);

        return $this->convertCallouts($processed);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function convertCallouts($text)
    {
        return preg_replace_callback(
            '/<blockquote>\\n<h4>(.*?)::(.*?)<\/h4>\\n(.*?)\\n<h4><\/h4>\\n<\/blockquote>/uis',
            function($matches) {
                $callout = '<div class="callout callout-'.strtolower($matches[1]).'">';
                $callout .= '<div class="callout-header"><h4>';
                $callout .= (empty($matches[2]) === false) ? $matches[2] : $matches[1];
                $callout .= '</h4></div>';
                $callout .= '<div class="callout-body">';
                $callout .= $matches[3];
                $callout .= '</div>';
                $callout .= '</div>';

                return $callout;
            },
            $text
        );
    }
}
