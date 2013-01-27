<?php

namespace spec\CliCrawler\Site;

use PHPSpec2\ObjectBehavior;

class Site extends ObjectBehavior
{
    protected $url = 'http://symfony.com';

    function let()
    {
        $this->beConstructedWith($this->url);
        $this->getUrl()->shouldReturn($this->url);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\Site\Site');
    }


    function it_should_be__check_is_internal_or_external_uri()
    {
        foreach ($this->get_data_for_internal_check() as $data) {
            $this->isInternal($data[0])->shouldReturn($data[1]);
        }
    }

    function it_should_be__normalize_host()
    {
        foreach ($this->get_data_normalize_check() as $data) {
            $this->normalizeHost($data[0])->shouldReturn($data[1]);
        }
    }

    function it_should_be_normalize_url_by_current_url()
    {
        $this->setUrl('http://dummy.com');
        $this->setCurrentUrl('http://dummy.com/sub/category');

        $this->normalizeUrl('/internal_1')->shouldReturn('http://dummy.com/internal_1');
        $this->normalizeUrl('http://dummy.com/internal_2')->shouldReturn('http://dummy.com/internal_2');
        $this->normalizeUrl('internal_3')->shouldReturn('http://dummy.com/sub/internal_3');
        $this->normalizeUrl('internal_3#ancor')->shouldReturn('http://dummy.com/sub/internal_3');
        $this->normalizeUrl('/')->shouldReturn('http://dummy.com');
        $this->normalizeUrl('../internal_3')->shouldReturn('http://dummy.com/internal_3');


        $this->setCurrentUrl('http://dummy.com/');
        $this->normalizeUrl('internal_3')->shouldReturn('http://dummy.com/internal_3');
    }

    function it_should_be_check_url_is_html_page(){
        $this->setUrl('http://dummy.com');
        $this->setCurrentUrl('http://dummy.com/category/');

        foreach($this->get_data_check_url_is_html_page() as $data){
            $this->isLinkToHtmlPage($data[0])->shouldReturn($data[1]);
        }
    }

    protected function get_data_for_internal_check()
    {
        return array(
            array('/symfony.html', true),
            array('#symfony.html', true),
            array('http://symfony.com/symfony.html', true),
            array('http://www.symfony.com/symfony.html', true),
            array('https://symfony.com/symfony.html', false),
            array('http://google.com/symfony.html', false),
            array('https://google.com/symfony.com/test.html', false),
            array('ftp://symfony.com/test.html', false),
        );
    }

    protected function get_data_normalize_check()
    {
        return array(
            array('symfony.com', 'symfony.com'),
            array('www.symfony.com', 'symfony.com'),
            array('wwW.symfonY.com', 'symfony.com'),
        );

    }

    protected function get_data_check_url_is_html_page()
    {
        return array(
            array('/test.html', true),
            array('/test', true),
            array('/test?t=23', true),
            array('/test.jpg', false),
            array('/test.gif', false),
            array('/test.png?test=1', false),
            array('/test.js', false),
            array('javascript:void()', false),
            array('mailto:asd@asd.ru', false),
        );
    }
}
