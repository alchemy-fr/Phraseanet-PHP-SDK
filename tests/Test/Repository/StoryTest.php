<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Story;
use PhraseanetSDK\EntityManager;

class StoryTest extends Repository
{
    public function testSearch()
    {
        $client = $this->getClient($this->getSampleResponse('repository/query/search_stories'));
        $storyRepo = new Story(new EntityManager($client));
        $query = $storyRepo->search();
        
        foreach ($query->getResults('stories') as $story) {
            $this->checkStory($story);
        }
    }
    
    public function testFind()
    {
        $client = $this->getClient($this->getSampleResponse('repository/query/search_stories'));
        $storyRepo = new Story(new EntityManager($client));
        $stories = $storyRepo->find(1, 1);
        $this->assertIsCollection($stories);
        foreach ($stories as $story) {
            $this->checkStory($story);
        }
    }
    
    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/story/idByDatabox'));
        $storyRepo = new Story(new EntityManager($client));
        $story = $storyRepo->findById(1, 1);
        $this->checkStory($story);
    }

}
