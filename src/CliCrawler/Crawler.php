<?php

namespace CliCrawler;

use CliCrawler\Console\Console;
use CliCrawler\HttpRequest\CurlRequest;
use CliCrawler\Parser\Parser;
use CliCrawler\Site\Site;
use CliCrawler\Template\Template;
use CliCrawler\Url\ArrayUrlCollector;

class Crawler
{
    protected $url;

    protected $site;

    protected $urlCollector;

    protected $parser;

    protected $template;

    protected $console;

    protected $curl;

    protected $reportDir;

    public function __construct()
    {
        $this->console = new Console($_SERVER['argv']);
        $this->url = $this->console->getUrl();
        $this->site = new Site($this->url);
        $this->curl = new CurlRequest();
        $this->urlCollector = new ArrayUrlCollector();
        $this->parser = new Parser($this->site, new CurlRequest());
        $this->template = new Template();

        $this->reportDir = ($this->console->getOption('reportDir'))
            ? $this->console->getOption('reportDir')
            : dirname(__FILE__) . '/../../reports/';
    }


    /**
     * Run parse
     */
    public function run()
    {
        $this->console->output(sprintf('Start parsing %s', $this->url), false, 1);
        $level = ($this->console->getOption('level'))
            ? $this->console->getOption('level')
            : 10;
        //add first url to parse
        $this->urlCollector->addUrl($this->url);
        $l = 0; //level counter
        while (count($this->urlCollector->getAllUnvisitedUrls()) && $l <= $level) {
            $this->console->output(sprintf('level %s find %s urls', $l, count($this->urlCollector->getAllUnvisitedUrls())), true, 2);
            foreach ($this->urlCollector->getAllUnvisitedUrls() as $url) {
                //set current url
                $this->site->setCurrentUrl($url);
                if ($content = $this->curl->request($url)) {
                    $internalUrls = $this->parser->getAllInternalLinks($content);
                    //add new url to url collector
                    $this->urlCollector->addUrls($internalUrls);
                    $this->urlCollector->setVisited($url);
                    //set quantity images on url
                    $this->urlCollector->setQuantity($url, $this->parser->getQuantityImgTags($content));
                } else {
                    //if trouble with getting content - remove url from collection
                    $this->urlCollector->removeUrl($url);
                }
                //output debug info
                if ($this->console->getOption('debug')) {
                    $this->console->output(sprintf('%s (%s)', $url, $this->urlCollector->getQuantity($url)), true, 4);
                }
            }
            $l++;
        }
        //generate report
        $this->generateReport();
    }


    /**
     * Generate report
     */
    public function generateReport()
    {
        $reportName = sprintf('report_%s.html', date('d.m.Y'));
        $this->console->output(sprintf('generate report %s', $reportName), true, 2);

        $report = $this->template->render(
            'report.html.php',
            array(
                'site' => $this->url,
                'urlCollector' => $this->urlCollector
            )
        );
        file_put_contents($this->reportDir . $reportName, $report);
        $this->console->output("Report was generated.\n", true, 2);
    }

    /**
     * Validation input options
     *
     * @return bool
     * @throws \Exception
     */
    public function validationInputOptions()
    {
        if (!is_writable($this->reportDir)) {
            throw new \Exception(sprintf('Report dir "%s" isn\'t writable.', $this->reportDir));
        }

        if (!parse_url($this->url, PHP_URL_HOST)) {
            $this->console->output(sprintf(" \n"), false, 0);
            throw new \Exception("Url  isn't set or not valid.");
        }

        return true;
    }


}
