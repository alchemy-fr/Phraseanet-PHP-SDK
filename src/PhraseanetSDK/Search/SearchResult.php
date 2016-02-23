<?php

namespace PhraseanetSDK\Search;

class SearchResult
{

    public static function fromList($valueType, array $values)
    {
        $results = [];

        foreach ($values as $value) {
            $results[] = self::fromValue($valueType, $value);
        }

        return $results;
    }

    public static function fromValue($valueType, \stdClass $value)
    {
        return new self($valueType, $value->databox_id, $value->collection_id, $value->record_id, $value->version);
    }

    const TYPE_RECORD = 0;

    const TYPE_STORY = 1;

    /**
     * @var int A SearchResult::TYPE_* constant
     */
    private $type = self::TYPE_RECORD;

    /**
     * @var int
     */
    private $databoxId;

    /**
     * @var int
     */
    private $collectionId;

    /**
     * @var int
     */
    private $recordId;

    /**
     * @var int
     */
    private $version;

    /**
     * @param int $type
     * @param int $databoxId
     * @param int $collectionId
     * @param int $recordId
     * @param int $version
     */
    public function __construct($type, $databoxId, $collectionId, $recordId, $version)
    {
        $this->type = $type;
        $this->databoxId = $databoxId;
        $this->collectionId = $collectionId;
        $this->recordId = $recordId;
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getDataboxId()
    {
        return $this->databoxId;
    }

    /**
     * @return int
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    /**
     * @return int
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
