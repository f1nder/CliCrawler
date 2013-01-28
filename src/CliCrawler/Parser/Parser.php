<?php

namespace CliCrawler\Parser;

use CliCrawler\Site\Site;

class Parser
{
    /**
     * @var Site
     */
    protected $site;


    function __construct($site, $httpRequest)
    {
        $this->site = $site;
    }

    /**
     * Parse all internal urls from content
     *
     * @param $content
     * @return array
     */
    public function getAllInternalLinks($content)
    {
        preg_match_all("#(?<=href=(\"|'))[^\"']+(?=(\"|'))#iUs", $content, $hrefs);

        $urls = array();
        foreach ($hrefs[0] as $url) {
            if ($this->site->isInternal($url) AND $url = $this->site->normalizeUrl($url)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * Get quantity img tags from content
     *
     * @param $content
     * @return int
     */
    public function getQuantityImgTags($content)
    {
        preg_match_all("#<img.*?>#i", $content, $imgs);

        return count($imgs[0]);
    }
}
