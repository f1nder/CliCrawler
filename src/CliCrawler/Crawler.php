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

    public function run()
    {
        $this->checkReportDirIsWritable();
        $this->console->output(sprintf('Start parsing %s', $this->url), false, 1);
        $level = ($this->console->getOption('level'))
            ? $this->console->getOption('level')
            : 10;

        $this->urlCollector->addUrl($this->url);
        $l = 0;
        while (count($this->urlCollector->getAllUnvisitedUrls()) && $l <= $level) {
            $this->console->output(sprintf('level %s find %s urls', $l, count($this->urlCollector->getAllUnvisitedUrls())), true, 2);
            foreach ($this->urlCollector->getAllUnvisitedUrls() as $url) {
                $this->site->setCurrentUrl($url);
                if ($content = $this->curl->request($url)) {
                    $internalUrls = $this->parser->getAllInternalLinks($content);
                    $this->urlCollector->addUrls($internalUrls);
                    $this->urlCollector->setVisited($url);
                    $this->urlCollector->setQuantity($url, $this->parser->getQuantityImgTags($content));
                } else {
                    $this->urlCollector->removeUrl($url);
                }
                if ($this->console->getOption('debug')) {
                    $this->console->output(sprintf('%s (%s)', $url, $this->urlCollector->getQuantity($url)), true, 4);
                }
            }
            $l++;
        }

        $this->generateReport();
    }

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
        $this->console->output(sprintf('Report was generated.', $reportName), true, 2);
    }

    protected function checkReportDirIsWritable()
    {
        if (!is_writable($this->reportDir)) {
            $this->console->output(sprintf('Report dir "%s" isn\'t writable.', $this->reportDir), true, 2);
            exit(1);
        }
    }
}
