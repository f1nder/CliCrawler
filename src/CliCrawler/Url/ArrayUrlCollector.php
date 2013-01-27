<?php

namespace CliCrawler\Url;

class ArrayUrlCollector implements UrlCollectorInterface
{
    /**
     * @var array
     */
    protected $urls = array();
    /**
     * @var array
     */
    protected $unvisited = array();
    /**
     * @var array
     */
    protected $quantity = array();

    /**
     * {@inheritdoc}
     */
    public function addUrl($url)
    {
        if (!$this->isExist($url)) {
            $this->urls[] = $url;
            $this->unvisited[$url] = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addUrls($urls)
    {
        foreach ($urls as $url) {
            $this->addUrl($url);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeUrl($url)
    {
        if (false !== $key = array_search($url, $this->urls)) {
            unset($this->urls[$key]);
            unset($this->unvisited[$url]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllUnvisitedUrls()
    {
        return array_keys($this->unvisited);
    }

    /**
     * {@inheritdoc}
     */
    public function isExist($url)
    {
        return in_array($url, $this->urls);
    }

    /**
     * {@inheritdoc}
     */
    public function setVisited($url)
    {
        unset($this->unvisited[$url]);
    }

    /**
     * {@inheritdoc}
     */
    public function isVisited($url)
    {
        return !isset($this->unvisited[$url]);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($url, $value)
    {
        $this->quantity[$url] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity($url)
    {
        return isset($this->quantity[$url]) ? $this->quantity[$url] : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlsSortedByQuantity()
    {
        arsort($this->quantity, SORT_NUMERIC);

        return array_keys($this->quantity);
    }


}
