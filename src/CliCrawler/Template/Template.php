<?php

namespace CliCrawler\Template;

class Template
{
    protected $templateDir;

    public function __construct($templateDir = null)
    {
        $this->templateDir = (is_null($templateDir))
            ? dirname(__FILE__) . '/../Resources/template'
            : $templateDir;
    }

    public function render($templateName, $vars = array())
    {
        extract($vars, EXTR_OVERWRITE);
        ob_start();
        require $this->getTemplatePath($templateName);
        $return = ob_get_contents();
        ob_end_clean();

        return $return;
    }

    protected function getTemplatePath($templateName)
    {
        return $this->templateDir. DIRECTORY_SEPARATOR . $templateName;
    }
}
