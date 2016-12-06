<?php

namespace Todaymade\Daux\Format\HTML;

use Todaymade\Daux\Format\HTML\ContentTypes\Markdown\ParsedownContentType;

class ParsedownGenerator extends Generator
{
    /**
     * @return array
     */
    public function getContentTypes()
    {

        return [
            'markdown' => new ParsedownContentType($this->daux->getParams()),
        ];
    }
}
