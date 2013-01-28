<?php

namespace CliCrawler\Site;

class Site
{
    protected $url;
    protected $currentUrl;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Check link is internal or external
     *
     * @param $url
     * @return bool
     */
    public function isInternal($url)
    {
        $urlHost = $this->normalizeHost(parse_url($url, PHP_URL_HOST));
        $siteUrlHost = $this->normalizeHost(parse_url($this->getUrl(), PHP_URL_HOST));

        $urlSchema = parse_url($url, PHP_URL_SCHEME);
        $siteUrlSchema = parse_url($this->getUrl(), PHP_URL_SCHEME);

        if ((($urlSchema == $siteUrlSchema && $urlHost == $siteUrlHost) || $urlHost == false) && $this->isLinkToHtmlPage($url)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Normalize host
     *
     * @param $host
     * @return bool|mixed
     */
    public function normalizeHost($host)
    {
        if ($host) {
            return str_ireplace('www.', '', strtolower($host));
        } else {
            return false;
        }
    }

    public function setCurrentUrl($currentUrl)
    {
        $this->currentUrl = $currentUrl;
    }

    public function getCurrentUrl()
    {
        return $this->currentUrl;
    }

    /**
     *  Try to normalize url to absolute
     *
     * @param $url
     * @return mixed|string
     */
    public function normalizeUrl($url)
    {
        #clear ancor
        $url = preg_replace('/#.+/', '', trim($url));

        if ($url == '/' || $url == $this->getUrl() . '/') {
            return $this->getUrl();
        } elseif (preg_match('#^(http|https)://#', $url)) {
            return $url;
        } elseif (preg_match('#^/#', $url)) {
            return $this->getUrl() . $url;
        } elseif (preg_match('/^\#/', $url)) {
            return false;
        }

        return $this->relativeToAbsolute($this->currentUrl, $url);
    }

    /**
     * Check link is to html page
     *
     * @param $url
     * @return bool
     */
    public function isLinkToHtmlPage($url)
    {
        $patterns = array(
            '#\.(png|jpeg|jpg|css|js|gif|ico|xml)(\?.*)?$#',
            '#^[a-z]+:[a-z0-9]#i',
        );
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert relative to absolute url
     *
     * @param $current
     * @param $relative
     * @return mixed
     */
    protected function relativeToAbsolute($current, $relative)
    {
      #  var_dump($current, $relative);
        $parse = parse_url($current);
        $root = $parse['scheme'] . "://" . $parse['host'];
        $p = strrpos(substr($current, 7), '/');
        if ($p) {
            $base = substr($current, 0, $p + 8);
        } else {
            $base = "$current/";
        }
        if (substr($relative, 0, 1) == '/') {
            $absolute = $root . $relative;
        } elseif (substr($relative, 0, 7) != "http://") {
            $absolute = $base . $relative;
        }

        $absolute = preg_replace("#/(?!\.\.)[^/]+/\.\./#", '/', $absolute);

        return $absolute;
    }
}
