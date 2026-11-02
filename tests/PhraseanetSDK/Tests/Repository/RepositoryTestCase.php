<?php

namespace PhraseanetSDK\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Entity\Result;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

abstract class RepositoryTestCase extends \PHPUnit\Framework\TestCase
{
    protected function getClient($response, $code = 200, $throwCurlException = false)
    {
        $answer = $throwCurlException
            ? new ConnectException('Connection refused', new Request('GET', 'http://my.domain.tld/'))
            : new Response($code, [], $response);

        $clientHttp = new GuzzleClient([
            'base_uri' => 'http://my.domain.tld/api/',
            'handler' => HandlerStack::create(new MockHandler([$answer])),
        ]);

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
        $this->assertIsString($basket->getName());
        $this->assertNotNull($basket->isUnread());
        $this->assertIsBool($basket->isUnread());
        $this->assertNotNull($basket->getDescription());
        $this->assertIsString($basket->getDescription());
        $this->assertNotNull($basket->getId());
        $this->assertIsInt($basket->getId());
        $this->assertNotNull($date = $basket->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $basket->getUpdatedOn());
        $this->assertIsDate($date);

        if ($basket->isValidationBasket()) {
            $this->assertNotNull($date = $basket->getExpiresOn());
            $this->assertIsDate($date);
            $this->assertNotNull($basket->getValidationInfo());
            $this->assertIsString($basket->getValidationInfo());
            $this->assertNotNull($basket->isValidationBasket());
            $this->assertIsBool($basket->isValidationBasket());
            $this->assertNotNull($basket->isValidationConfirmed());
            $this->assertIsBool($basket->isValidationConfirmed());
            $this->assertNotNull($basket->isValidationInitiator());
            $this->assertIsBool($basket->isValidationInitiator());
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
            $this->assertIsBool($agreement);
        }

        $this->assertNotNull($date = $choice->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($choice->getNote());
        $this->assertIsInt($choice->getNote());
        $this->assertNotNull($participant = $choice->getParticipant());
        $this->checkParticipant($participant);
    }

    protected function checkDataboxCollection($collection)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxCollection', $collection);
        /* @var $collection \PhraseanetSDK\Entity\DataboxCollection */
        $this->assertNotNull($collection->getBaseId());
        $this->assertIsInt($collection->getBaseId());
        $this->assertNotNull($collection->getCollectionId());
        $this->assertIsInt($collection->getCollectionId());
        $this->assertNotNull($collection->getName());
        $this->assertIsString($collection->getName());
        $this->assertNotNull($collection->getRecordAmount());
        $this->assertIsInt($collection->getRecordAmount());
        $this->assertNotNull($collection->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $collection->getLabels());
    }

    public function checkDataboxStructure($metadata)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxDocumentStructure', $metadata);
        /* @var $metadata \PhraseanetSDK\Entity\DataboxDocumentStructure */
        $this->assertNotNull($metadata->getId());
        $this->assertIsInt($metadata->getId());
        $this->assertNotNull($metadata->getNamespace());
        $this->assertIsString($metadata->getNamespace());
        $this->assertNotNull($metadata->getSource());
        $this->assertIsString($metadata->getSource());
        $this->assertNotNull($metadata->getTagname());
        $this->assertIsString($metadata->getTagname());
        $this->assertNotNull($metadata->getName());
        $this->assertIsString($metadata->getName());
        $this->assertNotNull($metadata->getSeparator());
        $this->assertIsString($metadata->getSeparator());
        $this->assertNotNull($metadata->getThesaurusBranch());
        $this->assertIsString($metadata->getThesaurusBranch());
        $this->assertNotNull($metadata->getType());
        $this->assertIsString($metadata->getType());
        $this->assertNotNull($metadata->isSearchable());
        $this->assertIsBool($metadata->isSearchable());
        $this->assertNotNull($metadata->isMultivalued());
        $this->assertIsBool($metadata->isMultivalued());
        $this->assertNotNull($metadata->isRequired());
        $this->assertIsBool($metadata->isRequired());
        $this->assertNotNull($metadata->isReadonly());
        $this->assertIsBool($metadata->isReadonly());
        $this->assertNotNull($metadata->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $metadata->getLabels());
    }

    public function checkDataBoxStatus($status)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\DataboxStatus', $status);
        /* @var $status \PhraseanetSDK\Entity\DataboxStatus */
        $this->assertNotNull($status->getBit());
        $this->assertIsInt($status->getBit());
        $this->assertNotNull($status->getLabelOn());
        $this->assertIsString($status->getLabelOn());
        $this->assertNotNull($status->getLabelOff());
        $this->assertIsString($status->getLabelOff());
        $this->assertNotNull($status->getImgOn());
        $this->assertIsString($status->getImgOn());
        $this->assertNotNull($status->getImgOff());
        $this->assertIsString($status->getImgOff());
        $this->assertNotNull($status->isSearchable());
        $this->assertIsBool($status->isSearchable());
        $this->assertNotNull($status->isPrintable());
        $this->assertIsBool($status->isPrintable());
        $this->assertNotNull($status->getLabels());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $status->getLabels());
    }

    protected function checkParticipant($participant)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\BasketValidationParticipant', $participant);
        /* @var $participant \PhraseanetSDK\Entity\BasketValidationParticipant */
        $this->assertIsBool($participant->isConfirmed());
        $this->assertNotNull($participant->canAgree());
        $this->assertIsBool($participant->canAgree());
        $this->assertNotNull($participant->canSeeOthers());
        $this->assertIsBool($participant->canSeeOthers());
        $this->assertNotNull($participant->getUser());
        $this->checkUser($participant->getUser());
    }

    protected function checkRecordStatus($status)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\RecordStatus', $status);
        /* @var $status \PhraseanetSDK\Entity\Status */
        $this->assertNotNull($status->getBit());
        $this->assertIsInt($status->getBit());
        $this->assertNotNull($status->getState());
        $this->assertIsBool($status->getState());
    }

    protected function checkQueryObject($query)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Query', $query);
        /* @var $query \PhraseanetSDK\Entity\Query */
        $this->assertNotNull($query->getOffsetStart());
        $this->assertIsInt($query->getOffsetStart());
        $this->assertNotNull($query->getPerPage());
        $this->assertIsInt($query->getPerPage());
        $this->assertNotNull($query->getTotalResults());
        $this->assertIsInt($query->getTotalResults());
        $this->assertNotNull($query->getError());
        $this->assertIsString($query->getError());
        $this->assertNotNull($query->getWarning());
        $this->assertIsString($query->getWarning());
        $this->assertNotNull($query->getSearchIndexes());
        $this->assertIsString($query->getSearchIndexes());
        $this->assertNotNull($query->getQuery());
        $this->assertIsString($query->getQuery());
        $this->assertNotNull($query->getQueryTime());
        $this->assertIsFloat($query->getQueryTime());
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
        $this->assertIsString($story->getId());
        $this->assertNotNull($story->getStoryId());
        $this->assertIsInt($story->getStoryId());
        $this->assertNotNull($story->getCollectionId());
        $this->assertIsInt($story->getCollectionId());
        $this->assertNotNull($story->getStoryId());
        $this->assertIsInt($story->getStoryId());
        $this->assertNotNull($story->getUuid());
        $this->assertIsString($story->getUuid());
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
        $this->assertIsString($suggestion->getValue());
        $this->assertNotNull($suggestion->isCurrent());
        $this->assertIsBool($suggestion->isCurrent());
        $this->assertNotNull($suggestion->getHits());
        $this->assertIsInt($suggestion->getHits());
    }

    protected function checkDatabox($databox)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\Databox', $databox);
        /* @var $databox \PhraseanetSDK\Entity\Databox */
        $this->assertNotNull($databox->getId());
        $this->assertNotNull($databox->getName());
        $this->assertNotNull($databox->getVersion());
        $this->assertNotNull($databox->getLabels());
        $this->assertIsInt($databox->getId());
        $this->assertIsString($databox->getVersion());
        $this->assertIsString($databox->getName());
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
        $this->assertIsString($record->getId());
        $this->assertNotNull($record->getRecordId());
        $this->assertIsInt($record->getRecordId());
        $this->assertNotNull($record->getCollectionId());
        $this->assertIsInt($record->getCollectionId());
        $this->assertNotNull($record->getRecordId());
        $this->assertIsInt($record->getRecordId());
        $this->assertNotNull($record->getMimeType());
        $this->assertIsString($record->getMimeType());
        $this->assertNotNull($record->getTitle());
        $this->assertIsString($record->getTitle());
        $this->assertNotNull($record->getOriginalName());
        $this->assertIsString($record->getOriginalName());
        $this->assertNotNull($record->getOriginalName());
        $this->assertIsString($record->getOriginalName());
        $this->assertNotNull($record->getSha256());
        $this->assertIsString($record->getSha256());
        $this->assertNotNull($record->getPhraseaType());
        $this->assertIsString($record->getPhraseaType());
        $this->assertNotNull($record->getUuid());
        $this->assertIsString($record->getUuid());
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
        $this->assertIsInt($feed->getId());
        $this->assertNotNull($feed->getTitle());
        $this->assertIsString($feed->getTitle());
        $this->assertNotNull($feed->getIcon());
        $this->assertIsString($feed->getIcon());
        $this->assertNotNull($feed->getSubTitle());
        $this->assertIsString($feed->getSubTitle());
        $this->assertNotNull($feed->getTotalEntries());
        $this->assertIsInt($feed->getTotalEntries());
        $this->assertNotNull($date = $feed->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $feed->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($feed->isPublic());
        $this->assertIsBool($feed->isPublic());
        $this->assertNotNull($feed->isReadonly());
        $this->assertIsBool($feed->isReadonly());
        $this->assertNotNull($feed->isDeletable());
        $this->assertIsBool($feed->isDeletable());
    }

    protected function checkTermsOfUse($cgus)
    {
        $this->assertTrue($cgus instanceof \PhraseanetSDK\Entity\DataboxTermsOfUse);
        /* @var $metadata \PhraseanetSDK\Entity\Metadata */
        $this->assertNotNull($cgus->getLocale());
        $this->assertIsString($cgus->getLocale());
        $this->assertNotNull($cgus->getTerms());
        $this->assertIsString($cgus->getTerms());
    }

    protected function checkMetadata($metadata)
    {
        $this->assertTrue($metadata instanceof \PhraseanetSDK\Entity\Metadata);
        /* @var $metadata \PhraseanetSDK\Entity\Metadata */
        $this->assertNotNull($metadata->getId());
        $this->assertIsInt($metadata->getId());
        $this->assertNotNull($metadata->getMetaStructureId());
        $this->assertIsInt($metadata->getMetaStructureId());
        $this->assertNotNull($metadata->getName());
        $this->assertIsString($metadata->getName());
        $this->assertNotNull($metadata->getValue());
        $this->assertIsString($metadata->getValue());
    }

    protected function checkPermalink($permalink)
    {
        $this->assertTrue($permalink instanceof \PhraseanetSDK\Entity\Permalink);
        /* @var $permalink \PhraseanetSDK\Entity\Permalink */
        $this->assertNotNull($permalink->getId());
        $this->assertIsInt($permalink->getId());
        $this->assertNotNull($permalink->isActivated());
        $this->assertIsBool($permalink->isActivated());
        $this->assertNotNull($permalink->getLabel());
        $this->assertIsString($permalink->getLabel());
        $this->assertNotNull($permalink->getUrl());
        $this->assertIsString($permalink->getUrl());
        $this->assertNotNull($permalink->getPageUrl());
        $this->assertIsString($permalink->getPageUrl());
        $this->assertNotNull($date = $permalink->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $permalink->getCreatedOn());
        $this->assertIsDate($date);
    }

    protected function checkSubdef($subdef)
    {
        if (null === $subdef) {
            return;
        }
        $this->assertTrue($subdef instanceof \PhraseanetSDK\Entity\Subdef);
        /* @var $subdef \PhraseanetSDK\Entity\Subdef */
        $this->assertNotNull($subdef->getPlayerType());
        $this->assertIsString($subdef->getPlayerType());
        $this->assertNotNull($subdef->getMimeType());
        $this->assertIsString($subdef->getMimeType());
        $this->assertNotNull($subdef->getName());
        $this->assertIsString($subdef->getName());
        $this->assertNotNull($subdef->getHeight());
        $this->assertIsInt($subdef->getHeight());
        $this->assertNotNull($subdef->getWidth());
        $this->assertIsInt($subdef->getWidth());
        $this->assertNotNull($subdef->getFileSize());
        $this->assertIsInt($subdef->getFileSize());
        $this->assertNotNull($subdef->getPermalink());
        $this->checkPermalink($subdef->getPermalink());
    }

    protected function checkQuarantine($quarantine)
    {
        $this->assertTrue($quarantine instanceof \PhraseanetSDK\Entity\Quarantine);
        /* @var $quarantine \PhraseanetSDK\Entity\Quarantine */
        $this->assertNotNull($quarantine->getId());
        $this->assertIsInt($quarantine->getId());
        $this->assertNotNull($quarantine->getBaseId());
        $this->assertIsInt($quarantine->getBaseId());
        $this->assertNotNull($quarantine->getOriginalName());
        $this->assertIsString($quarantine->getOriginalName());
        $this->assertNotNull($quarantine->getSha256());
        $this->assertIsString($quarantine->getSha256());
        $this->assertNotNull($quarantine->getUuid());
        $this->assertIsString($quarantine->getUuid());
        $this->assertNotNull($quarantine->isForced());
        $this->assertIsBool($quarantine->isForced());
        $this->assertNotNull($date = $quarantine->getUpdatedOn());
        $this->assertIsDate($date);
        $this->assertNotNull($date = $quarantine->getCreatedOn());
        $this->assertIsDate($date);
        $this->assertIsCollection($checks = $quarantine->getChecks());
        $this->assertNotNull($session = $quarantine->getSession());
        $this->checkQuarantineSession($session);
        foreach ($checks as $check) {
            $this->assertIsString($check);
        }
    }

    protected function checkFeedEntry($entry)
    {
        $this->assertTrue($entry instanceof \PhraseanetSDK\Entity\FeedEntry);
        /* @var $entry \PhraseanetSDK\Entity\FeedEntry */
        $this->assertNotNull($entry->getId());
        $this->assertIsInt($entry->getId());
        $this->assertNotNull($entry->getFeedId());
        $this->assertIsInt($entry->getFeedId());
        $this->assertNotNull($entry->getAuthorEmail());
        $this->assertIsString($entry->getAuthorEmail());
        $this->assertNotNull($entry->getAuthorName());
        $this->assertIsString($entry->getAuthorName());
        $this->assertNotNull($entry->getTitle());
        $this->assertIsString($entry->getTitle());
        $this->assertNotNull($entry->getSubtitle());
        $this->assertIsString($entry->getSubtitle());
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
        $this->assertIsInt($item->getId());
        $this->assertNotNull($record = $item->getRecord());
        $this->checkRecord($record);
    }

    protected function checkQuarantineSession($session)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\QuarantineSession', $session);
        $this->assertNotNull($session->getId());
        $this->assertIsInt($session->getId());
        $this->assertNotNull($session->getUser());
        $this->checkUser($session->getUser());
    }

    protected function checkRecordCaption($caption)
    {
        $this->assertInstanceOf('\PhraseanetSDK\Entity\RecordCaption', $caption);
        $this->assertNotNull($caption->getMetaStructureId());
        $this->assertIsInt($caption->getMetaStructureId());
        $this->assertNotNull($caption->getName());
        $this->assertIsString($caption->getName());
        $this->assertNotNull($caption->getValue());
        $this->assertIsString($caption->getValue());
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
