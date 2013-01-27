<?php

namespace spec\CliCrawler\Parser;


use PHPSpec2\ObjectBehavior;

class Parser extends ObjectBehavior
{
    protected $url = 'http://dummy.com';

    /**
     * @param CliCrawler\Site\Site $site
     * @param CliCrawler\HttpRequest\RequestInterface $httpRequest
     */
    function let($site, $httpRequest)
    {
        $this->beConstructedWith($site, $httpRequest);
    }

    function it_should_be_initializable()
    {
        # $this->shouldHaveType('CliCrawler\Parser\Parser');
    }

    /**
     * @param CliCrawler\Site\Site $site
     * @param CliCrawler\HttpRequest\RequestInterface $httpRequest
     */
    function it_should_parse_all_internal_href_links($site, $httpRequest)
    {
        $site->isInternal('/internal_1?test=1&sf2=awesome')->willReturn(true);
        $site->isInternal('http://dummy.com/internal_2')->willReturn(true);
        $site->isInternal('http://ya.ru/external_1')->willReturn(false);
        $site->isInternal('http://ya.ru/external_2/dummy.com')->willReturn(false);
        $site->isInternal('http://www.dummy.com/internal_3')->willReturn(true);

        $site->normalizeUrl('/internal_1?test=1&sf2=awesome')->willReturn('http://dummy.com/internal_1?test=1&sf2=awesome');
        $site->normalizeUrl('http://dummy.com/internal_2')->willReturn('http://dummy.com/internal_2');
        $site->normalizeUrl('http://www.dummy.com/internal_3')->willReturn('http://www.dummy.com/internal_3');

        $httpRequest->request($this->url)->willReturn($this->getDummyHtml());

        $this->getAllInternalLinks($this->getDummyHtml())->shouldReturn(
            array(
                'http://dummy.com/internal_1?test=1&sf2=awesome',
                'http://dummy.com/internal_2',
                'http://www.dummy.com/internal_3',
            )
        );

    }

    /**
     * @param CliCrawler\Site\Site $site
     * @param CliCrawler\HttpRequest\RequestInterface $httpRequest
     */
    function it_should_parse_img_tags($httpRequest)
    {
        $httpRequest->request($this->url)->willReturn($this->getDummyHtml());

        $this->getQuantityImgTags($this->getDummyHtml())->shouldReturn(3);
    }

    function getDummyHtml()
    {
        return <<<HTML
<html>
<body>
<img src="/image.jpg" title="dummy">
  <a href="/internal_1?test=1&sf2=awesome" class="" style="">Internal</a>
  <p>Dummy test</p>
  <a href="http://ya.ru/external_1" class="" style="">External</a>
  <div>Dummy div</div>
  <a href="http://ya.ru/external_2/dummy.com" class="" style="">External</a>
  <a class=".classer"  href="http://dummy.com/internal_2" >External</a>
  <img src="/img">
  <IMG style="margin-left:15px;" class="er" id="dummy" src="/img" />
  <a class=".classer"  href="http://www.dummy.com/internal_3" >External</a>
</body>
</html>
HTML;

    }
}
