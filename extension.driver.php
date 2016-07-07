<?php

class extension_Asset_Pipeline_Less extends Extension
{
    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page' => '/extension/asset_pipeline/',
                'delegate' => 'RegisterPlugins',
                'callback' => 'register'
            )
        );
    }

    function register($context) {
        $context['plugins']['less'] = array('output_type' => 'css', 'driver' => $this);
    }

    public function compile($content, $import_dir = null)
    {
        $result = shell_exec(
            'node ' . escapeshellarg(__DIR__ . '/lib/compile.js') . ' ' . escapeshellarg($content) . ' ' . escapeshellarg($import_dir)
        );
        $pos = strpos($result, ' ');
        return array(substr($result, 0, $pos) => substr($result, $pos + 1));
    }
}
