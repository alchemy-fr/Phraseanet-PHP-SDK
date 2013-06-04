<?php

namespace PhraseanetSDK\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client as GuzzleClient;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

abstract class Repository extends \PHPUnit_Framework_TestCase
{

    protected function getClient($response, $code = 200, $throwCurlException = false)
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(
                $code
                , null
                , $response
            )
        );

        $clientHttp = new GuzzleClient('http://my.domain.tld/api/v1');
        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        if ($throwCurlException) {
            $clientHttp->getEventDispatcher()->addListener('request.before_send', function(\Guzzle\Common\Event $event) {
                    throw new \Guzzle\Http\Exception\CurlException();
                });
        }

        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        return new Client('123456', '654321', new GuzzleAdapter($clientHttp), $logger);
    }

    protected function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../../resources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }

    protected function checkBasket($basket)
    {
        $this->assertTrue($basket instanceof \PhraseanetSDK\Entity\Basket);
        /* @var $basket \PhraseanetSDK\Entity\Basket */
        $this->assertNotNull($basket->getName());
        $this->assertInternalType('string', $basket->getName());
        $this->assertNotNull($basket->isUnread());
        $this->assertInternalType('boolean', $basket->isUnread());
        $this->assertNotNull($basket->getDescription());
        $this->assertInternalType('string', $basket->getDescription());
        $this->assertNotNull($basket->getPusherUsrId());
        $this->assertInternalType('integer', $basket->getPusherUsrId());
        $this->assertNotNull($date = $basket->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $basket->getUpdatedOn());
        $this->assertIsDate($date);

        if ($basket->isValidationBasket()) {
            $this->assertNotNull($date = $basket->getExpiresOn());
            $this->assertIsDate($date);
            $this->assertNotNull($basket->getValidationInfos());
            $this->assertInternalType('string', $basket->getValidationInfos());
            $this->assertNotNull($basket->isValidationBasket());
            $this->assertInternalType('boolean', $basket->isValidationBasket());
            $this->assertNotNull($basket->isValidationConfirmed());
            $this->assertInternalType('boolean', $basket->isValidationConfirmed());
            $this->assertNotNull($basket->isValidationInitiator());
            $this->assertInternalType('boolean', $basket->isValidationInitiator());
            $this->assertNotNull($users = $basket->getValidationUsers());
            if (! $users instanceof Doctrine\Common\Collection) {
                $basket->getBasketId();
            }

            $this->assertIsCollection($users);

            foreach ($users as $user) {
                $this->checkParticipant($user);
            }
        }
    }

    protected function checkBasketElement($basketElement)
    {
        $this->assertTrue($basketElement instanceof \PhraseanetSDK\Entity\BasketElement);
        /* @var $basketElement \PhraseanetSDK\Entity\BasketElement */

        $this->assertNotNull($basketElement->getOrder());
        $this->assertInternalType('integer', $basketElement->getOrder());
        $this->assertNotNull($basketElement->getBasketElementId());
        $this->assertInternalType('integer', $basketElement->getBasketElementId());
        $this->assertNotNull($basketElement->isValidationItem());
        $this->assertInternalType('boolean', $basketElement->isValidationItem());
        $this->assertNotNull($record = $basketElement->getRecord());
        $this->checkRecord($record);

        if ($basketElement->isValidationItem()) {
            $this->assertNotNull($choices = $basketElement->getValidationChoices());
            $this->assertIsCollection($choices);

            foreach ($choices as $choice) {
                $this->checkValidationChoice($choice);
            }

            $this->assertTrue(in_array($basketElement->getAgreement(), array(null, true, false)));
            $this->assertNotNull($basketElement->getNote());
            $this->assertInternalType('string', $basketElement->getNote());
        }
    }

    protected function checkValidationChoice($choice)
    {
        $this->assertTrue($choice instanceof \PhraseanetSDK\Entity\BasketValidationChoice);
        /* @var $choice \PhraseanetSDK\Entity\BasketValidationChoice */

        if (null !== $agreement = $choice->getAgreement()) {
            $this->assertInternalType('boolean', $agreement);
        }

        $this->assertNotNull($date = $choice->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($choice->getNote());
        $this->assertInternalType('string', $choice->getNote());
        $this->assertNotNull($participant = $choice->getValidationUser());
        $this->checkParticipant($participant);
    }

    protected function checkDataboxCollection($collection)
    {
        $this->assertTrue($collection instanceof \PhraseanetSDK\Entity\DataboxCollection);
        /* @var $collection \PhraseanetSDK\Entity\DataboxCollection */
        $this->assertNotNull($collection->getBaseId());
        $this->assertInternalType('integer', $collection->getBaseId());
        $this->assertNotNull($collection->getCollectionId());
        $this->assertInternalType('integer', $collection->getCollectionId());
        $this->assertNotNull($collection->getName());
        $this->assertInternalType('string', $collection->getName());
        $this->assertNotNull($collection->getRecordAmount());
        $this->assertInternalType('integer', $collection->getRecordAmount());
    }

    public function checkDataboxStructure($metadatas)
    {
        $this->assertTrue($metadatas instanceof \PhraseanetSDK\Entity\DataboxDocumentStructure);
        /* @var $metadatas \PhraseanetSDK\Entity\DataboxDocumentStructure */
        $this->assertNotNull($metadatas->getId());
        $this->assertInternalType('integer', $metadatas->getId());
        $this->assertNotNull($metadatas->getNamespace());
        $this->assertInternalType('string', $metadatas->getNamespace());
        $this->assertNotNull($metadatas->getSource());
        $this->assertInternalType('string', $metadatas->getSource());
        $this->assertNotNull($metadatas->getTagname());
        $this->assertInternalType('string', $metadatas->getTagname());
        $this->assertNotNull($metadatas->getName());
        $this->assertInternalType('string', $metadatas->getName());
        $this->assertNotNull($metadatas->getSeparator());
        $this->assertInternalType('string', $metadatas->getSeparator());
        $this->assertNotNull($metadatas->getThesaurusBranch());
        $this->assertInternalType('string', $metadatas->getThesaurusBranch());
        $this->assertNotNull($metadatas->getType());
        $this->assertInternalType('string', $metadatas->getType());
        $this->assertNotNull($metadatas->isIndexable());
        $this->assertInternalType('boolean', $metadatas->isIndexable());
        $this->assertNotNull($metadatas->isMultivalue());
        $this->assertInternalType('boolean', $metadatas->isMultivalue());
        $this->assertNotNull($metadatas->isRequired());
        $this->assertInternalType('boolean', $metadatas->isRequired());
        $this->assertNotNull($metadatas->isReadonly());
        $this->assertInternalType('boolean', $metadatas->isReadonly());
    }

    public function checkDataBoxStatus($status)
    {
        $this->assertTrue($status instanceof \PhraseanetSDK\Entity\DataboxStatus);
        /* @var $status \PhraseanetSDK\Entity\DataboxStatus */
        $this->assertNotNull($status->getBit());
        $this->assertInternalType('integer', $status->getBit());
        $this->assertNotNull($status->getLabelOn());
        $this->assertInternalType('string', $status->getLabelOn());
        $this->assertNotNull($status->getLabelOff());
        $this->assertInternalType('string', $status->getLabelOff());
        $this->assertNotNull($status->getImgOn());
        $this->assertInternalType('string', $status->getImgOn());
        $this->assertNotNull($status->getImgOff());
        $this->assertInternalType('string', $status->getImgOff());
        $this->assertNotNull($status->isSearchable());
        $this->assertInternalType('boolean', $status->isSearchable());
        $this->assertNotNull($status->isPrintable());
        $this->assertInternalType('boolean', $status->isPrintable());
    }

    protected function checkParticipant($participant)
    {
        $this->assertTrue($participant instanceof \PhraseanetSDK\Entity\BasketValidationParticipant);
        /* @var $participant \PhraseanetSDK\Entity\BasketValidationParticipant */
        $this->assertNotNull($participant->getUsrName());
        $this->assertInternalType('string', $participant->getUsrName());
        $this->assertNotNull($participant->getUsrId());
        $this->assertInternalType('integer', $participant->getUsrId());
        $this->assertNotNull($participant->isConfirmed());
        $this->assertInternalType('boolean', $participant->isConfirmed());
        $this->assertNotNull($participant->canAgree());
        $this->assertInternalType('boolean', $participant->canAgree());
        $this->assertNotNull($participant->canSeeOthers());
        $this->assertInternalType('boolean', $participant->canSeeOthers());
    }

    protected function checkRecordStatus($status)
    {
        $this->assertTrue($status instanceof \PhraseanetSDK\Entity\RecordStatus);
        /* @var $status \PhraseanetSDK\Entity\Status */
        $this->assertNotNull($status->getBit());
        $this->assertInternalType('integer', $status->getBit());
        $this->assertNotNull($status->getState());
        $this->assertInternalType('boolean', $status->getState());
    }

    protected function checkQueryObject($query)
    {
        $this->assertTrue($query instanceof \PhraseanetSDK\Entity\Query);
        /* @var $query \PhraseanetSDK\Entity\Query */
        $this->assertNotNull($query->getOffsetStart());
        $this->assertInternalType('integer', $query->getOffsetStart());
        $this->assertNotNull($query->getPerPage());
        $this->assertInternalType('integer', $query->getPerPage());
        $this->assertNotNull($query->getAvailableResults());
        $this->assertInternalType('integer', $query->getAvailableResults());
        $this->assertNotNull($query->getTotalResults());
        $this->assertInternalType('integer', $query->getTotalResults());
        $this->assertNotNull($query->getError());
        $this->assertInternalType('string', $query->getError());
        $this->assertNotNull($query->getWarning());
        $this->assertInternalType('string', $query->getWarning());
        $this->assertNotNull($query->getSearchIndexes());
        $this->assertInternalType('string', $query->getSearchIndexes());
        $this->assertNotNull($query->getQuery());
        $this->assertInternalType('string', $query->getQuery());
        $this->assertNotNull($query->getQueryTime());
        $this->assertInternalType('float', $query->getQueryTime());
        $this->assertNotNull($query->getSuggestions());

        $suggestions = $query->getSuggestions();
        $this->assertTrue($suggestions instanceof \Doctrine\Common\Collections\ArrayCollection);

        foreach ($suggestions as $suggestion) {
            $this->checkQuerySuggestions($suggestion);
        }

        $results = $query->getResults();
        $this->assertTrue($results instanceof ArrayCollection);

        foreach ($results as $record) {
            $this->checkRecord($record);
        }
    }

    protected function checkStory($story)
    {
        $this->assertTrue($story instanceof \PhraseanetSDK\Entity\Story);
        /* @var $suggestion \PhraseanetSDK\Entity\QuerySuggestion */
        $this->assertNotNull($story->getDataboxId());
        $this->assertInternalType('integer', $story->getDataboxId());
        $this->assertNotNull($story->getCollectionId());
        $this->assertInternalType('integer', $story->getCollectionId());
        $this->assertNotNull($story->getStoryId());
        $this->assertInternalType('integer', $story->getStoryId());
        $this->assertNotNull($story->getUuid());
        $this->assertInternalType('string', $story->getUuid());
        $this->assertNotNull($date = $story->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $story->getUpdatedOn());
        $this->assertIsDate($date);

        $this->assertNotNull($subdef = $story->getThumbnail());
        $this->assertTrue($subdef instanceof \PhraseanetSDK\Entity\Subdef);
        $this->assertNotNull($metas = $story->getMetadatas());
        $this->assertTrue($metas instanceof ArrayCollection);
    }

    protected function checkQuerySuggestions($suggestion)
    {
        $this->assertTrue($suggestion instanceof \PhraseanetSDK\Entity\QuerySuggestion);
        /* @var $suggestion \PhraseanetSDK\Entity\QuerySuggestion */
        $this->assertNotNull($suggestion->getValue());
        $this->assertInternalType('string', $suggestion->getValue());
        $this->assertNotNull($suggestion->isCurrent());
        $this->assertInternalType('boolean', $suggestion->isCurrent());
        $this->assertNotNull($suggestion->getHits());
        $this->assertInternalType('integer', $suggestion->getHits());
    }

    protected function checkDatabox($databox)
    {
        $this->assertTrue($databox instanceof \PhraseanetSDK\Entity\Databox);
        /* @var $databox \PhraseanetSDK\Entity\Databox */
        $this->assertNotNull($databox->getDataboxId());
        $this->assertNotNull($databox->getName());
        $this->assertNotNull($databox->getVersion());
        $this->assertInternalType('integer', $databox->getDataboxId());
        $this->assertInternalType('string', $databox->getVersion());
        $this->assertInternalType('string', $databox->getName());
    }

    protected function checkTechnicalInformation($technical)
    {
        $this->assertTrue($technical instanceof \PhraseanetSDK\Entity\Technical);
        /* @var $technical \PhraseanetSDK\Entity\Technical */

        $this->assertNotNull($technical->getName());
        $this->assertNotNull($technical->getValue());
    }

    protected function checkRecord($record)
    {
        $this->assertTrue($record instanceof \PhraseanetSDK\Entity\Record);
        /* @var $record \PhraseanetSDK\Entity\Record */
        $this->assertNotNull($record->getDataboxId());
        $this->assertInternalType('integer', $record->getDataboxId());
        $this->assertNotNull($record->getCollectionId());
        $this->assertInternalType('integer', $record->getCollectionId());
        $this->assertNotNull($record->getRecordId());
        $this->assertInternalType('integer', $record->getRecordId());
        $this->assertNotNull($record->getMimeType());
        $this->assertInternalType('string', $record->getMimeType());
        $this->assertNotNull($record->getTitle());
        $this->assertInternalType('string', $record->getTitle());
        $this->assertNotNull($record->getOriginalName());
        $this->assertInternalType('string', $record->getOriginalName());
        $this->assertNotNull($record->getOriginalName());
        $this->assertInternalType('string', $record->getOriginalName());
        $this->assertNotNull($record->getSha256());
        $this->assertInternalType('string', $record->getSha256());
        $this->assertNotNull($record->getPhraseaType());
        $this->assertInternalType('string', $record->getPhraseaType());
        $this->assertNotNull($record->getUuid());
        $this->assertInternalType('string', $record->getUuid());
        $this->assertNotNull($date = $record->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $record->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($subdef = $record->getThumbnail());
        $this->checkSubdef($subdef);
        $this->assertNotNull($technicalInformations = $record->getTechnicalInformations());
        $this->assertIsCollection($technicalInformations);

        foreach ($technicalInformations as $information) {
            $this->checkTechnicalInformation($information);
        }
    }

    protected function checkFeed($feed)
    {
        $this->assertTrue($feed instanceof \PhraseanetSDK\Entity\Feed);
        /* @var $feed \PhraseanetSDK\Entity\Feed */
        $this->assertNotNull($feed->getId());
        $this->assertInternalType('integer', $feed->getId());
        $this->assertNotNull($feed->getTitle());
        $this->assertInternalType('string', $feed->getTitle());
        $this->assertNotNull($feed->getIcon());
        $this->assertInternalType('string', $feed->getIcon());
        $this->assertNotNull($feed->getSubTitle());
        $this->assertInternalType('string', $feed->getSubTitle());
        $this->assertNotNull($feed->getTotalEntries());
        $this->assertInternalType('int', $feed->getTotalEntries());
        $this->assertNotNull($date = $feed->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $feed->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($feed->isPublic());
        $this->assertInternalType('boolean', $feed->isPublic());
        $this->assertNotNull($feed->isReadonly());
        $this->assertInternalType('boolean', $feed->isReadonly());
        $this->assertNotNull($feed->isDeletable());
        $this->assertInternalType('boolean', $feed->isDeletable());
    }

    protected function checkCgus($cgus)
    {
        $this->assertTrue($cgus instanceof \PhraseanetSDK\Entity\Cgus);
        /* @var $metadatas \PhraseanetSDK\Entity\Metadatas */
        $this->assertNotNull($cgus->getLocale());
        $this->assertInternalType('string', $cgus->getLocale());
        $this->assertNotNull($cgus->getTerms());
        $this->assertInternalType('string', $cgus->getTerms());
    }

    protected function checkMetadatas($metadatas)
    {
        $this->assertTrue($metadatas instanceof \PhraseanetSDK\Entity\Metadatas);
        /* @var $metadatas \PhraseanetSDK\Entity\Metadatas */
        $this->assertNotNull($metadatas->getMetaId());
        $this->assertInternalType('integer', $metadatas->getMetaId());
        $this->assertNotNull($metadatas->getMetaStructureId());
        $this->assertInternalType('integer', $metadatas->getMetaStructureId());
        $this->assertNotNull($metadatas->getName());
        $this->assertInternalType('string', $metadatas->getName());
        $this->assertNotNull($metadatas->getValue());
        $this->assertInternalType('string', $metadatas->getValue());
    }

    protected function checkPermalink($permalink)
    {
        $this->assertTrue($permalink instanceof \PhraseanetSDK\Entity\Permalink);
        /* @var $permalink \PhraseanetSDK\Entity\Permalink */
        $this->assertNotNull($permalink->getId());
        $this->assertInternalType('integer', $permalink->getId());
        $this->assertNotNull($permalink->isActivated());
        $this->assertInternalType('boolean', $permalink->isActivated());
        $this->assertNotNull($permalink->getLabel());
        $this->assertInternalType('string', $permalink->getLabel());
        $this->assertNotNull($permalink->getUrl());
        $this->assertInternalType('string', $permalink->getUrl());
        $this->assertNotNull($permalink->getPageUrl());
        $this->assertInternalType('string', $permalink->getPageUrl());
        $this->assertNotNull($date = $permalink->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $permalink->getCreatedOn());
        $this->assertIsDate($date);
    }

    protected function checkSubdef($subdef)
    {
        $this->assertTrue($subdef instanceof \PhraseanetSDK\Entity\Subdef);
        /* @var $subdef \PhraseanetSDK\Entity\Subdef */
        $this->assertNotNull($subdef->getPlayerType());
        $this->assertInternalType('string', $subdef->getPlayerType());
        $this->assertNotNull($subdef->getMimeType());
        $this->assertInternalType('string', $subdef->getMimeType());
        $this->assertNotNull($subdef->getName());
        $this->assertInternalType('string', $subdef->getName());
        $this->assertNotNull($subdef->getHeight());
        $this->assertInternalType('integer', $subdef->getHeight());
        $this->assertNotNull($subdef->getWidth());
        $this->assertInternalType('integer', $subdef->getWidth());
        $this->assertNotNull($subdef->getFileSize());
        $this->assertInternalType('integer', $subdef->getFileSize());
        $this->assertNotNull($subdef->getPermalink());
        $this->checkPermalink($subdef->getPermalink());
    }

    protected function checkQuarantine($quarantine)
    {
        $this->assertTrue($quarantine instanceof \PhraseanetSDK\Entity\Quarantine);
        /* @var $quarantine \PhraseanetSDK\Entity\Quarantine */
        $this->assertNotNull($quarantine->getId());
        $this->assertInternalType('integer', $quarantine->getId());
        $this->assertNotNull($quarantine->getBaseId());
        $this->assertInternalType('integer', $quarantine->getBaseId());
        $this->assertNotNull($quarantine->getOriginalName());
        $this->assertInternalType('string', $quarantine->getOriginalName());
        $this->assertNotNull($quarantine->getSha256());
        $this->assertInternalType('string', $quarantine->getSha256());
        $this->assertNotNull($quarantine->getUuid());
        $this->assertInternalType('string', $quarantine->getUuid());
        $this->assertNotNull($quarantine->isForced());
        $this->assertInternalType('boolean', $quarantine->isForced());
        $this->assertNotNull($date = $quarantine->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $quarantine->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertIsCollection($checks = $quarantine->getChecks());
        $this->assertNotNull($session = $quarantine->getQuarantineSession());
        $this->checkQuarantineSession($session);
        foreach ($checks as $check) {
            $this->assertInternalType('string', $check);
        }
    }

    protected function checkFeedEntry($entry)
    {
        $this->assertTrue($entry instanceof \PhraseanetSDK\Entity\FeedEntry);
        /* @var $entry \PhraseanetSDK\Entity\FeedEntry */
        $this->assertNotNull($entry->getId());
        $this->assertInternalType('integer', $entry->getId());
        $this->assertNotNull($entry->getFeedId());
        $this->assertInternalType('integer', $entry->getFeedId());
        $this->assertNotNull($entry->getAuthorEmail());
        $this->assertInternalType('string', $entry->getAuthorEmail());
        $this->assertNotNull($entry->getAuthorName());
        $this->assertInternalType('string', $entry->getAuthorName());
        $this->assertNotNull($entry->getTitle());
        $this->assertInternalType('string', $entry->getTitle());
        $this->assertNotNull($entry->getSubtitle());
        $this->assertInternalType('string', $entry->getSubtitle());
        $this->assertNotNull($date = $entry->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $entry->getCreatedOn());
        $this->assertIsDate($date);

        $this->assertIsCollection($items = $entry->getItems());

        foreach ($items as $item) {
            $this->checkFeedEntryItem($item);
        }
    }

    protected function checkFeedEntryItem($item)
    {
        $this->assertTrue($item instanceof \PhraseanetSDK\Entity\FeedEntryItem);
        /* @var $item \PhraseanetSDK\Entity\FeedEntryItem */
        $this->assertNotNull($item->getItemId());
        $this->assertInternalType('integer', $item->getItemId());
        $this->assertNotNull($record = $item->getRecord());
        $this->checkRecord($record);
    }

    protected function checkQuarantineSession($session)
    {
        $this->assertTrue($session instanceof \PhraseanetSDK\Entity\QuarantineSession);
        $this->assertNotNull($session->getId());
        $this->assertInternalType('integer', $session->getId());
        $this->assertNotNull($session->getUsrid());
        $this->assertInternalType('integer', $session->getUsrid());
    }

    protected function checkRecordCaption($caption)
    {
        $this->assertTrue($caption instanceof \PhraseanetSDK\Entity\RecordCaption);
        $this->assertNotNull($caption->getMetaStructureId());
        $this->assertInternalType('integer', $caption->getMetaStructureId());
        $this->assertNotNull($caption->getName());
        $this->assertInternalType('string', $caption->getName());
        $this->assertNotNull($caption->getValue());
        $this->assertInternalType('string', $caption->getValue());
    }

    protected function assertIsCollection($collection)
    {
        $this->assertTrue($collection instanceof \Doctrine\Common\Collections\ArrayCollection);
        $this->assertGreaterThan(0, $collection->count());
    }

    protected function assertIsDate($date)
    {
        $this->assertTrue($date instanceof \DateTime);
    }
}
