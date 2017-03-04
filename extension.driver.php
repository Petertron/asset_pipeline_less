<?php

class Extension_Asset_Pipeline_Less extends Extension
{
    public $error;

    public function getOutputType()
    {
        return 'css';
    }

    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page' => '/extension/asset_pipeline/',
                'delegate' => 'RegisterPreprocessors',
                'callback' => 'register'
            )
        );
    }

    public function register($context)
    {
        $context['preprocessors']['less'] = $this;
    }

    public function convert($content, $import_dir)
    {
        $process = proc_open(
            'node lib/compile.js '
                . escapeshellarg($content) . ' '
                . escapeshellarg($import_dir),
            array(
                0 => array('pipe', 'r'),
                1 => array('pipe', 'w'),
                2 => array('pipe', 'w')
            ),
            $pipes, __DIR__
        );

        if ($error = stream_get_contents($pipes[2])) {
            $this->error = $error;
        } else {
            $content = stream_get_contents($pipes[1]);
        }

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        proc_close($process);

        return $content;
    }

}
