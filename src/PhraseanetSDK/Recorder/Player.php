<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder;

use Guzzle\Common\Exception\GuzzleException;
use PhraseanetSDK\ApplicationInterface;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Recorder\Storage\StorageInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Player
{
    const USER_AGENT = 'Phraseanet SDK Player';

    private $adapter;
    private $storage;

    public function __construct(APIGuzzleAdapter $adapter, StorageInterface $storage)
    {
        $this->adapter = $adapter;
        $this->storage = $storage;
    }

    public function play(OutputInterface $output = null)
    {
        $data = $this->storage->fetch();
        foreach ($data as $request) {
            $this->output(sprintf(
                "--> Executing request %s %s",
                $request['method'],
                $request['path']
            ), $output);

            $start = microtime(true);
            $error = null;
            try {
                $this->adapter->call(
                    $request['method'],
                    $request['path'],
                    $request['query'],
                    $request['post-fields'],
                    array(),
                    array('User-Agent' => sprintf('%s/%s', self::USER_AGENT, ApplicationInterface::VERSION))
                );
            } catch (GuzzleException $e) {
                $error = $e;
            }
            $duration = microtime(true) - $start;

            if (null !== $error) {
                $this->output(sprintf(
                    "    Query <error>failed</error> : %s.\n",
                    $e->getMessage()
                ), $output);
            } else {
                $this->output(sprintf(
                    "    Query took <comment>%f</comment>.\n",
                    $duration
                ), $output);
            }
        }
    }

    private function output($message, OutputInterface $output = null)
    {
        if (null !== $output) {
            $output->writeln($message);
        }
    }
}
