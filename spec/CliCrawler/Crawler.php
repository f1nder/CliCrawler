<?php

namespace spec\CliCrawler;

use PHPSpec2\ObjectBehavior;

class Crawler extends ObjectBehavior
{
    /**
     * @param CliCrawler\Factory\Factory $factory
     */
    function let($factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\Crawler');
    }
}
