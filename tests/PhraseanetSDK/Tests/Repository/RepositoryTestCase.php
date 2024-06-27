<?php

namespace PhraseanetSDK\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Entity\Result;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client as GuzzleClient;

abstract class RepositoryTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getClient($response, $code = 200, $throwCurlException = false)
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response($code, null, $response));

        $clientHttp = new GuzzleClient('http://my.domain.tld/api/v1');
        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        if ($throwCurlException) {
            $clientHttp->getEventDispatcher()->addListener('request.before_send', function (\Guzzle\Common\Event $event) {
                    throw new \Guzzle\Http\Exception\CurlException();
                });
        }

        return new APIGuzzleAdapter(new GuzzleAdapter($clientHttp));
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
        $this->assertNotNull($basket->getId());
        $this->assertInternalType('integer', $basket->getId());
        $this->assertNotNull($date = $basket->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $basket->getUpdatedOn());
        $this->assertIsDate($date);

        if ($basket->isValidationBasket()) {
            $this->assertNotNull($date = $basket->getExpiresOn());
            $this->assertIsDate($date);
            $this->assertNotNull($basket->getValidationInfo());
            $this->assertInternalType('string', $basket->getValidationInfo());
            $this->assertNotNull($basket->isValidationBasket());
            $this->assertInternalType('boolean', $basket->isValidationBasket());
            $this->assertNotNull($basket->isValidationConfirmed());
            $this->assertInternalType('boolean', $basket->isValidationConfirmed());
            $this->assertNotNull($basket->isValidationInitiator());
            $this->assertInternalType('boolean', $basket->isValidationInitiator());
            $this->assertNotNull($users = $basket->getValidationUsers());
            if (! $users instanceof ArrayCollection) {
                $basket->getId();
            }

            $this->assertIsCollection($users);

            foreach ($users as $user) {
                $this->checkParticipant($user);
            }
        }
    }

    protected function checkValidationChoice($choice)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\BasketValidationChoice', $choice);
        /* @var $choice \PhraseanetSDK\Entity\BasketValidationChoice */

        if (null !== $agreement = $choice->getAgreement()) {
            $this->assertInternalType('boolean', $agreement);
        }

        $this->assertNotNull($date = $choice->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($choice->getNote());
        $this->assertInternalType('integer', $choice->getNote());
        $this->assertNotNull($participant = $choice->getParticipant());
        $this->checkParticipant($participant);
    }

    protected function checkDataboxCollection($collection)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxCollection', $collection);
        /* @var $collection \PhraseanetSDK\Entity\DataboxCollection */
        $this->assertNotNull($collection->getBaseId());
        $this->assertInternalType('integer', $collection->getBaseId());
        $this->assertNotNull($collection->getCollectionId());
        $this->assertInternalType('integer', $collection->getCollectionId());
        $this->assertNotNull($collection->getName());
        $this->assertInternalType('string', $collection->getName());
        $this->assertNotNull($collection->getRecordAmount());
        $this->assertInternalType('integer', $collection->getRecordAmount());
        $this->assertNotNull($collection->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $collection->getLabels());
    }

    public function checkDataboxStructure($metadata)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxDocumentStructure', $metadata);
        /* @var $metadata \PhraseanetSDK\Entity\DataboxDocumentStructure */
        $this->assertNotNull($metadata->getId());
        $this->assertInternalType('integer', $metadata->getId());
        $this->assertNotNull($metadata->getNamespace());
        $this->assertInternalType('string', $metadata->getNamespace());
        $this->assertNotNull($metadata->getSource());
        $this->assertInternalType('string', $metadata->getSource());
        $this->assertNotNull($metadata->getTagname());
        $this->assertInternalType('string', $metadata->getTagname());
        $this->assertNotNull($metadata->getName());
        $this->assertInternalType('string', $metadata->getName());
        $this->assertNotNull($metadata->getSeparator());
        $this->assertInternalType('string', $metadata->getSeparator());
        $this->assertNotNull($metadata->getThesaurusBranch());
        $this->assertInternalType('string', $metadata->getThesaurusBranch());
        $this->assertNotNull($metadata->getType());
        $this->assertInternalType('string', $metadata->getType());
        $this->assertNotNull($metadata->isSearchable());
        $this->assertInternalType('boolean', $metadata->isSearchable());
        $this->assertNotNull($metadata->isMultivalued());
        $this->assertInternalType('boolean', $metadata->isMultivalued());
        $this->assertNotNull($metadata->isRequired());
        $this->assertInternalType('boolean', $metadata->isRequired());
        $this->assertNotNull($metadata->isReadonly());
        $this->assertInternalType('boolean', $metadata->isReadonly());
        $this->assertNotNull($metadata->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $metadata->getLabels());
    }

    public function checkDataBoxStatus($status)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxStatus', $status);
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
        $this->assertNotNull($status->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $status->getLabels());
    }

    protected function checkParticipant($participant)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\BasketValidationParticipant', $participant);
        /* @var $participant \PhraseanetSDK\Entity\BasketValidationParticipant */
        $this->assertInternalType('boolean', $participant->isConfirmed());
        $this->assertNotNull($participant->canAgree());
        $this->assertInternalType('boolean', $participant->canAgree());
        $this->assertNotNull($participant->canSeeOthers());
        $this->assertInternalType('boolean', $participant->canSeeOthers());
        $this->assertNotNull($participant->getUser());
        $this->checkUser($participant->getUser());
    }

    protected function checkRecordStatus($status)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\RecordStatus', $status);
        /* @var $status \PhraseanetSDK\Entity\Status */
        $this->assertNotNull($status->getBit());
        $this->assertInternalType('integer', $status->getBit());
        $this->assertNotNull($status->getState());
        $this->assertInternalType('boolean', $status->getState());
    }

    protected function checkQueryObject($query)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Query', $query);
        /* @var $query \PhraseanetSDK\Entity\Query */
        $this->assertNotNull($query->getOffsetStart());
        $this->assertInternalType('integer', $query->getOffsetStart());
        $this->assertNotNull($query->getPerPage());
        $this->assertInternalType('integer', $query->getPerPage());
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
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $suggestions);

        foreach ($suggestions as $suggestion) {
            $this->checkQuerySuggestions($suggestion);
        }

        $results = $query->getResults();
        $this->assertInstanceOf('PhraseanetSDK\Entity\Result', $results);

        foreach ($results as $record) {
            $this->checkRecord($record);
        }
    }

    protected function checkStory($story)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Story', $story);
        /* @var $story \PhraseanetSDK\Entity\Story */
        $this->assertNotNull($story->getId());
        $this->assertInternalType('string', $story->getId());
        $this->assertNotNull($story->getStoryId());
        $this->assertInternalType('integer', $story->getStoryId());
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

        $subdef = $story->getThumbnail();
        $this->checkSubdef($subdef);
        $metas = $story->getMetadata();
        $this->assertNotNull($metas);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $metas);
    }

    protected function checkQuerySuggestions($suggestion)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\QuerySuggestion', $suggestion);
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
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Databox', $databox);
        /* @var $databox \PhraseanetSDK\Entity\Databox */
        $this->assertNotNull($databox->getId());
        $this->assertNotNull($databox->getName());
        $this->assertNotNull($databox->getVersion());
        $this->assertNotNull($databox->getLabels());
        $this->assertInternalType('integer', $databox->getId());
        $this->assertInternalType('string', $databox->getVersion());
        $this->assertInternalType('string', $databox->getName());
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $databox->getLabels());
    }

    protected function checkTechnicalInformation($technical)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Technical', $technical);
        /* @var $technical \PhraseanetSDK\Entity\Technical */

        $this->assertNotNull($technical->getName());
        $this->assertNotNull($technical->getValue());
    }

    protected function checkRecord($record)
    {
        $this->assertTrue($record instanceof \PhraseanetSDK\Entity\Record);
        /* @var $record \PhraseanetSDK\Entity\Record */
        $this->assertNotNull($record->getId());
        $this->assertInternalType('string', $record->getId());
        $this->assertNotNull($record->getRecordId());
        $this->assertInternalType('integer', $record->getRecordId());
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
        $subdef = $record->getThumbnail();
        $this->checkSubdef($subdef);
        $this->assertNotNull($technicalInformations = $record->getTechnicalInformation());
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

    protected function checkTermsOfUse($cgus)
    {
        $this->assertTrue($cgus instanceof \PhraseanetSDK\Entity\DataboxTermsOfUse);
        /* @var $metadata \PhraseanetSDK\Entity\Metadata */
        $this->assertNotNull($cgus->getLocale());
        $this->assertInternalType('string', $cgus->getLocale());
        $this->assertNotNull($cgus->getTerms());
        $this->assertInternalType('string', $cgus->getTerms());
    }

    protected function checkMetadata($metadata)
    {
        $this->assertTrue($metadata instanceof \PhraseanetSDK\Entity\Metadata);
        /* @var $metadata \PhraseanetSDK\Entity\Metadata */
        $this->assertNotNull($metadata->getId());
        $this->assertInternalType('integer', $metadata->getId());
        $this->assertNotNull($metadata->getMetaStructureId());
        $this->assertInternalType('integer', $metadata->getMetaStructureId());
        $this->assertNotNull($metadata->getName());
        $this->assertInternalType('string', $metadata->getName());
        $this->assertNotNull($metadata->getValue());
        $this->assertInternalType('string', $metadata->getValue());
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
    }

    protected function checkSubdef($subdef)
    {
        if (null === $subdef) {
            return;
        }
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
        $this->assertNotNull($session = $quarantine->getSession());
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
        $this->assertInstanceOf('\PhraseanetSDK\Entity\FeedEntryItem', $item);
        /* @var $item \PhraseanetSDK\Entity\FeedEntryItem */
        $this->assertNotNull($item->getId());
        $this->assertInternalType('integer', $item->getId());
        $this->assertNotNull($record = $item->getRecord());
        $this->checkRecord($record);
    }

    protected function checkQuarantineSession($session)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\QuarantineSession', $session);
        $this->assertNotNull($session->getId());
        $this->assertInternalType('integer', $session->getId());
        $this->assertNotNull($session->getUser());
        $this->checkUser($session->getUser());
    }

    protected function checkRecordCaption($caption)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\RecordCaption', $caption);
        $this->assertNotNull($caption->getMetaStructureId());
        $this->assertInternalType('integer', $caption->getMetaStructureId());
        $this->assertNotNull($caption->getName());
        $this->assertInternalType('string', $caption->getName());
        $this->assertNotNull($caption->getValue());
        $this->assertInternalType('string', $caption->getValue());
    }

    protected function checkUser($user)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\User', $user);
        $this->assertNotNull($user->getEmail());
        $this->assertNotNull($user->getLogin());
    }

    protected function assertIsCollection($collection)
    {
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $collection);
    }

    protected function assertIsDate($date)
    {
        $this->assertInstanceOf('\DateTime', $date);
    }
}
