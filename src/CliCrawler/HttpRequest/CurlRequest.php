<?php

namespace CliCrawler\HttpRequest;

class CurlRequest
{
    private $lastUrl = false;

    private $lastContent;

    public function request($url)
    {
        if (false == $this->lastUrl || $url !== $this->lastUrl) {
            $this->lastUrl = $url;
            $this->lastContent = $this->curl($url);

            return $this->lastContent;
        }

        return $this->lastContent;
    }

    protected function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (200 !== $info['http_code'] || false === stripos($info['content_type'], 'text/html') ) {
            return false;
        }

        return $result;
    }
}
