<?php

namespace spec\CliCrawler\Console;

use PHPSpec2\ObjectBehavior;

class Console extends ObjectBehavior
{
    protected $argv = array(
        'bin/crawler',
        'http://site.com',
        '--level=3',
        '--debug',
    );

    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\Console\Console');
    }

    function let()
    {
        $this->beConstructedWith($this->argv);
    }

    function it_should_be_return_level_option()
    {
        $this->getOption('level')->shouldReturn(3);
    }

    function it_should_be_return_true_by_default()
    {
        $this->getOption('debug')->shouldReturn(true);
    }

    function it_should_be_return_false_by_default()
    {
        $this->getOption('some-unset-option')->shouldReturn(false);
    }

    function it_should_be_return_url()
    {
        $this->getUrl()->shouldReturn('http://site.com');
    }

}
