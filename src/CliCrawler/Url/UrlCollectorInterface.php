<?php

namespace CliCrawler\Url;

interface UrlCollectorInterface
{
    /**
     * Add url to collection return false if url exist
     *
     * @param $url mixed
     * @return boolean
     */
    public function addUrl($url);

    /**
     * Add url to collection return false if url exist
     *
     * @param $urls mixed
     * @return boolean
     */
    public function addUrls($urls);

    /**
     * Remove url from collection
     *
     * @param $url mixed
     * @return boolean
     */
    public function removeUrl($url);

    /**
     * Return all urls, sorted by quantity tags
     *
     * @return array
     */
    public function getUrls();

    /**
     * Check url exist in collection
     *
     * @param $url
     * @return boolean
     */
    public function isExist($url);

    /**
     * Return all not visited url
     *
     * @return array
     */
    public function getAllUnvisitedUrls();

    /**
     * Set visited mark to url
     */
    public function setVisited($url);

    /**
     * Check url visited or not
     *
     * @param $url
     * @return boolean
     */
    public function isVisited($url);

    /**
     * Set tags quantity for url
     *
     * @param $url
     * @param $value
     * @return mixed
     */
    public function setQuantity($url, $value);

    /**
     * Get tags quantity by url
     *
     * @param $url
     * @return integer boolean
     */
    public function getQuantity($url);

    /**
     * Get tags quantity by url
     *
     * @return array
     */
    public function getUrlsSortedByQuantity();
}
