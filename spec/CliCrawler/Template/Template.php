<?php

namespace spec\CliCrawler\Template;

use PHPSpec2\ObjectBehavior;

class Template extends ObjectBehavior
{
    protected $templateDir;

    function let()
    {
        $this->templateDir = dirname(__FILE__);
        $this->beConstructedWith($this->templateDir);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\Template\Template');
    }

    function it_should_be_render_template()
    {
        $urls = array(
            'tes1',
            'test2',
            'test3',
        );
        $this->render('test.html', array('urls' => $urls))->shouldReturn(<<<TEXT
<ul>
            <li>tes1</li>
            <li>test2</li>
            <li>test3</li>
    </ul>
TEXT
        );
    }


}
