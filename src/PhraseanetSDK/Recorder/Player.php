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
            $this->output($output, sprintf(
                "--> Executing request %s %s", $request['method'], $request['path']
            ));

            $start = microtime(true);
            $error = null;
            try {
                $this->adapter->call($request['method'], $request['path'], $request['query'], $request['post-fields'], array(), array('User-Agent' => sprintf('%s/%s', self::USER_AGENT, ApplicationInterface::VERSION)));
            } catch (GuzzleException $e) {
                $error = $e;
            }
            $duration = microtime(true) - $start;

            if (null !== $error) {
                $this->output($output, sprintf(
                    "    Query <error>failed</error> : %s.\n",
                    $e->getMessage()
                ));
            } else {
                $this->output($output, sprintf(
                    "    Query took <comment>%f</comment>.\n",
                    $duration
                ));
            }
        }
    }

    private function output(OutputInterface $output = null, $message)
    {
        if (null !== $output) {
            $output->writeln($message);
        }
    }
}
