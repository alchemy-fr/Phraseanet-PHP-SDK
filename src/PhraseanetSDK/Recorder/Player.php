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

use Guzzle\Http\ClientInterface;
use PhraseanetSDK\Recorder\Storage\StorageInterface;

class Player
{
    private $client;
    private $storage;
    private $serializer;

    public function __construct(ClientInterface $client, StorageInterface $storage, RequestSerializer $serializer)
    {
        $this->client = $client;
        $this->storage = $storage;
        $this->serializer = $serializer;
    }

    public function play()
    {
        $data = $this->storage->fetch();

        foreach ($data as $serializedRequest) {
            $request = $this->serializer->unserialize($this->client, $serializedRequest);
            $request->send();
        }
    }
}
