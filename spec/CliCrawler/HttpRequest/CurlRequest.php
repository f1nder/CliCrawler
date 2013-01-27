<?php

namespace spec\CliCrawler\HttpRequest;

use PHPSpec2\ObjectBehavior;

class CurlRequest extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\HttpRequest\CurlRequest');
    }

    function it_should_be_return_page_source(){
        $this->request('http://symfony.com')->shouldReturn(file_get_contents('http://symfony.com'));
    }

    function it_should_be_return_link_only_to_html_page(){
        $this->request('http://symfony.com')->shouldReturn(file_get_contents('http://symfony.com'));
        $this->request('http://symfony.com/images/common/logo/logo_symfony_header.png')->shouldReturn(false);
    }
}
