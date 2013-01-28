<?php

namespace CliCrawler\Console;

class Console
{
    protected $argv;

    protected $options = array();

    public function __construct($argv)
    {
        //slice exec name
        array_shift($argv);
        $this->argv = $argv;
        $this->parseOptions();
    }

    public function getOption($option)
    {
        return (isset($this->options[$option])) ? $this->options[$option] : false;
    }

    public function getUrl()
    {
        return (isset($this->argv[0])) ? $this->argv[0] : false;
    }

    public function output($message, $newline = true, $prefix = 0)
    {
        $message = str_repeat('-', $prefix) . $message;
        $message = ($newline) ? "\n" . $message : $message;
        print($message);
    }

    /**
     * Parse and set input options
     */
    protected function parseOptions()
    {
        foreach ($this->argv as $a) {
            if (substr($a, 0, 2) == '--') {
                $eq = strpos($a, '=');
                if ($eq !== false) {
                    $this->options[substr($a, 2, $eq - 2)] = is_numeric(substr($a, $eq + 1))
                        ? (int)substr($a, $eq + 1)
                        : substr($a, $eq + 1);
                } else {
                    $k = substr($a, 2);
                    if (!isset($this->options[$k])) {
                        $this->options[$k] = true;
                    }
                }
            }
        }
    }
}
