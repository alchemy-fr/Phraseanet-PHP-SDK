UPGRADE
=======

This is a list of backwards compatibility (BC) breaks introduced in PhraseanetSDK:

# 0.5.0

 * Cgus entity has been renamed to DataboxTermsOfUse
 * Metadatas entity has been renamed to Metadata
 * StoryMetadataBag entity has been removed
 * Method Basket::getBasketId() has been renamed to Basket::getId()
 * Method Basket::getValidationInfos() has been renamed to Basket::getValidationInfo()
 * Method Basket::getPusherUsrId() has been replaced by Basket::getPusher() which returns an User entity
 * Method BasketElement::getBasketElementId() has been renamed to BasketElement::getId()
 * Method BasketValidationChoice::getValidationUser() has been renamed to BasketValidationChoice::getParticipant()
 * Method BasketValidationParticipant::getUsrId() and BasketValidationParticipant::getUsrName()
   have been removed and replace BasketValidationParticipant::getUser() which returns an User entity
 * Method Databox::getDataboxId() has been renamed to Databox::getId()
 * Method DataboxDocumentStructure::isIndexable() has been renamed to DataboxDocumentStructure::isSearchable()
 * Method DataboxDocumentStructure::isMultivalue() has been renamed to DataboxDocumentStructure::isMultivalued()
 * Method FeedEntryItem::getItemId() has been renamed to FeedEntryItem::getId()
 * Method Metadata::getMetaId() has been renamed to Metadata::getId()
 * Method Quarantine::getQuarantineSession() has been renamed to Quarantine::getSession()
 * Method QuarantineSession::getUsrId() has been renamed to Quarantine::getUser() which returns an User entity
 * Method Query::getAvailableResults() has been removed
 * Method Query::getResults() now return a Result entity object
 * Method Record::getTechnicalInformations() has been renamed to Record::getTechnicalInformation()
 * Method Record::getMetadatas() has been renamed to Record::getMetadata()
 * Method Record::getSubdefsByDevicesAndMimeTypes() has been removed
 * Method Story::getMetadatas() has been renamed to Story::getMetadata() and now return an ArrayCollection
   object of metadata <metadataKey>:<metadataValue>
 * Method EntityManager::getLoader() has been renamed to EntityManager::getUploader()
 * Configuration service 'phraseanet-sdk.recorder.config' for Silex Service Provider has been renamed to 'recorder.config'
 * Configuration service 'phraseanet-sdk.cache.config' for Silex Service Provider has been renamed to 'cache.config'
 * Configuration service 'phraseanet-sdk.config' for Silex Service Provider has been renamed to 'sdk.config'
 * Method Application::getLoader() has been renamed Application::getUploader()
 * Method Signature Application::create() now takes A GuzzleAdapter object as second parameter
 * Method EntityManager::HydrateEntity() has been removed
 * Method EntityManager::getEntity() has been removed
  