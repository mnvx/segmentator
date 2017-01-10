<?php

class TomitaParser
{
    /**
     * @var string Path to Yandex`s Tomita-parser binary
     */
    protected $execPath;

    /**
     * @var string Path to Yandex`s Tomita-parser configuration file
     */
    protected $configPath;

    /**
     * @param string $execPath Path to Yandex`s Tomita-parser binary
     * @param string $configPath Path to Yandex`s Tomita-parser configuration file
     */
    public function __construct($execPath, $configPath)
    {
        $this->execPath = $execPath;
        $this->configPath = $configPath;
    }

    public function run($text)
    {
        $descriptors = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'w')  // stderr
        );

        $cmd = sprintf('%s %s', $this->execPath, $this->configPath);
        $process = proc_open($cmd, $descriptors, $pipes, dirname($this->configPath));

        if (is_resource($process))
        {

            fwrite($pipes[0], $text);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            return $this->processTextResult($output);
        }

        throw new \Exception('proc_open fails');
    }

    /**
     * Обработка текстового результата
     * @param string $text
     * @return string[]
     */
    public function processTextResult($text)
    {
        return array_filter(explode("\n", $text));
    }

}

$parser = new TomitaParser('/home/mnv/tmp/tomita/tomita-linux64', '/home/mnv/tmp/tomita/config.proto');
var_dump($parser->run('Предложение раз. Предложение два.'));

