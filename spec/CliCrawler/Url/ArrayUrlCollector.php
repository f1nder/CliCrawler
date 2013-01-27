<?php

namespace spec\CliCrawler\Url;

use PHPSpec2\ObjectBehavior;

class ArrayUrlCollector extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('CliCrawler\Url\ArrayUrlCollector');
    }
    function it_should_implement_url_collector_interface()
    {
        $this->shouldHaveType('CliCrawler\Url\UrlCollectorinterface');
    }

    function it_should_be_add_url_to_collection(){
        $this->addUrl('http://dummy.com');
        $this->getUrls()->shouldReturn(array('http://dummy.com'));
    }

    function it_should_be_remove_url_from_collection(){
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy1.com');
        $this->addUrl('http://dummy2.com');

        $this->removeUrl('http://dummy2.com');
        $this->getUrls()->shouldReturn(array('http://dummy.com', 'http://dummy1.com'));
    }

    function it_should_be_add_only_unique_url_to_collection(){
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy2.com');
        $this->getUrls()->shouldReturn(array('http://dummy.com', 'http://dummy2.com'));
    }

    function it_should_set_url_visited(){
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy2.com');

        $this->setVisited('http://dummy2.com');

        $this->isVisited('http://dummy2.com')->shouldReturn(true);
        $this->isVisited('http://dummy.com')->shouldReturn(false);
    }

    function it_should_return_only_unvisited_url(){
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy2.com');

        $this->setVisited('http://dummy2.com');

        $this->getAllUnvisitedUrls()->shouldReturn(array('http://dummy.com'));
    }
    
    function it_should_set_quantity_and_set_only_exist_url(){
        $this->addUrl('http://dummy.com');
        $this->addUrl('http://dummy2.com');

        $this->setQuantity('http://dummy.com', 5);
        $this->setQuantity('http://dummy2.com', 15);

        $this->getQuantity('http://dummy.com')->shouldReturn(5);
        $this->getQuantity('http://dummy2.com')->shouldReturn(15);
        $this->getQuantity('http://dummy3.com')->shouldReturn(0);
    }
}
