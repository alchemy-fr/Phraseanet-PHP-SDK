<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Entity\DataboxCollection;
use PhraseanetSDK\Entity\Quarantine;
use PhraseanetSDK\Entity\Record;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Http\ApiClient;
use PhraseanetSDK\Http\APIGuzzleAdapter;

class Uploader
{
    /** @var ApiClient */
    private $adapter;

    /** EntityManager */
    private $em;

    /**
     * @param ApiClient $adapter
     * @param EntityManager $em
     */
    public function __construct(ApiClient $adapter, EntityManager $em)
    {
        $this->adapter = $adapter;
        $this->em = $em;
    }

    /**
     * Uploads a file to Phraseanet.
     *
     * @param string $file The path to the file to upload
     * @param integer|DataboxCollection $collection The base_id of the collection or a DataboxCollection object
     * @param int|null $behavior Set to 0 to force record and bypass checks, Set to 1 to force quarantine.
     * @param int|null $status A binary string to set status bits.
     *
     * @return Record|Quarantine
     *
     * @throws RuntimeException In case an error occurred
     */
    public function upload($file, $collection, $behavior = null, $status = null)
    {
        $postFields = array(
            'base_id' => $collection instanceof DataboxCollection ? $collection->getBaseId() : $collection,
        );

        if (null !== $behavior) {
            $postFields['forceBehavior'] = $behavior;
        }

        if (null !== $status) {
            $postFields['status'] = $status;
        }

        $response = $this->adapter->call('POST', 'v1/records/add/', array(), $postFields, array(
            'file' => $file,
        ));

        switch ((int)$response->getResult()->entity) {
            case 0:
                $matches = array();
                preg_match('/\/records\/(\d+)\/(\d+)\//', $response->getResult()->url, $matches);
                if (3 !== count($matches)) {
                    throw new RuntimeException('Unable to find the record item back');
                }

                return $this->em->getRepository('record')->findById($matches[1], $matches[2]);
            case 1:
                $matches = array();
                preg_match('/quarantine\/item\/(\d+)\//', $response->getResult()->url, $matches);
                if (2 !== count($matches)) {
                    throw new RuntimeException('Unable to find the quarantine item back');
                }

                return $this->em->getRepository('quarantine')->findById($matches[1]);
            default:
                throw new RuntimeException('Unable to detect the output');
        }
    }
}
