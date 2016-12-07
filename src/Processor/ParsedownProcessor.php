<?php

namespace Todaymade\Daux\Extension;

use Todaymade\Daux\Format\HTML\ContentTypes\ParsedownContentType;

/**
 * Class ParsedownProcessor
 *
 * @package Todaymade\Daux\Extension
 */
class ParsedownProcessor extends \Todaymade\Daux\Processor
{
    /**
     * Replace the markdown content type with Parsedown
     * @return array
     */
    public function addContentType()
    {
        return ['markdown' => new ParsedownContentType($this->daux->getParams())];
    }
}