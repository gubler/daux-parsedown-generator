<?php

namespace Todaymade\Daux\Extension;

use Todaymade\Daux\Tree\Root;

class Processor extends \Todaymade\Daux\Processor
{
    public function addGenerators()
    {
        return ['parsedown_generator' => '\Todaymade\Daux\Extension\ParsedownGenerator'];
    }
}