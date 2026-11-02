<?php

namespace PhraseanetSDK\Tests\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhraseanetSDK\Cache\CacheMiddleware;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Http\GuzzleAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class GuzzleAdapterTest extends \PHPUnit\Framework\TestCase
{
    private array $history = [];

    private function adapter(array $answers): GuzzleAdapter
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($answers));
        $stack->push(Middleware::history($this->history));

        return new GuzzleAdapter(new Client(['base_uri' => 'http://domain.tld/api/', 'handler' => $stack]));
    }

    private function lastRequest()
    {
        return end($this->history)['request'];
    }

    public function testCreateAddsTheApiMountPointOnce()
    {
        $this->assertSame('http://domain.tld/api/', GuzzleAdapter::create('http://domain.tld/')->getBaseUrl());
        $this->assertSame('http://domain.tld/api/', GuzzleAdapter::create('http://domain.tld/api/')->getBaseUrl());
    }

    public function testCreateRejectsAnInvalidEndpoint()
    {
        $this->expectException(InvalidArgumentException::class);

        GuzzleAdapter::create(42);
    }

    public function testUserAgentAndAcceptHeadersAreSent()
    {
        $adapter = $this->adapter([new Response(200), new Response(200)]);
        $adapter->setUserAgent('agent');

        $adapter->call('GET', 'v1/x/');
        $this->assertSame('agent', $this->lastRequest()->getHeaderLine('User-Agent'));
        $this->assertSame('application/json', $this->lastRequest()->getHeaderLine('Accept'));

        $adapter->setExtended(true);
        $adapter->call('GET', 'v1/x/');
        $this->assertSame('application/vnd.phraseanet.record-extended+json', $this->lastRequest()->getHeaderLine('Accept'));
    }

    public function testGetSendsTheQueryAndReturnsTheBody()
    {
        $adapter = $this->adapter([new Response(200, [], 'body')]);

        $this->assertSame('body', $adapter->call('GET', 'v1/x/', ['a' => 'b', 'c' => ['d', 'e']]));

        $request = $this->lastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/api/v1/x/', $request->getUri()->getPath());
        parse_str($request->getUri()->getQuery(), $query);
        $this->assertSame(['a' => 'b', 'c' => ['d', 'e']], $query);
    }

    public function testPostSendsAForm()
    {
        $adapter = $this->adapter([new Response(200)]);

        $adapter->call('POST', 'v1/x/', [], ['bases' => [1, 2], 'query' => 'a b']);

        parse_str((string) $this->lastRequest()->getBody(), $fields);
        $this->assertSame(['bases' => ['1', '2'], 'query' => 'a b'], $fields);
    }

    public function testPostSendsJsonWhenAsked()
    {
        $adapter = $this->adapter([new Response(200)]);

        $adapter->call('POST', 'v1/x/', [], ['a' => 'b'], [], ['Content-Type' => 'application/json']);

        $this->assertSame('{"a":"b"}', (string) $this->lastRequest()->getBody());
        $this->assertSame('application/json', $this->lastRequest()->getHeaderLine('Content-Type'));
    }

    public function testPostSendsFilesAsMultipart()
    {
        $file = tempnam(sys_get_temp_dir(), 'sdk');
        file_put_contents($file, 'content');
        $adapter = $this->adapter([new Response(200)]);

        $adapter->call('POST', 'v1/x/', [], ['base' => 1], ['file' => $file]);
        unlink($file);

        $body = (string) $this->lastRequest()->getBody();
        $this->assertStringContainsString('name="base"', $body);
        $this->assertStringContainsString('name="file"', $body);
        $this->assertStringContainsString('content', $body);
    }

    public function testPostFieldsOnGetAreRefused()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->adapter([new Response(200)])->call('GET', 'v1/x/', [], ['a' => 'b']);
    }

    public function testConnectionErrorsBecomeRuntimeExceptions()
    {
        $this->expectException(RuntimeException::class);

        $this->adapter([new ConnectException('down', new Request('GET', 'x'))])->call('GET', 'v1/x/');
    }

    public function testBadResponsesKeepTheStatusAndTheBody()
    {
        try {
            $this->adapter([new Response(404, [], 'missing')])->call('GET', 'v1/x/');
            $this->fail('An exception was expected.');
        } catch (BadResponseException $exception) {
            $this->assertSame(404, $exception->getStatusCode());
            $this->assertSame('missing', $exception->getResponseBody());
        }
    }

    public function testCacheAvoidsTheSecondGet()
    {
        $calls = 0;
        $adapter = $this->cachedAdapter($calls);

        $this->assertSame('one', $adapter->call('GET', 'v1/x/'));
        $this->assertSame('one', $adapter->call('GET', 'v1/x/'));
        $this->assertSame(1, $calls);
    }

    public function testCacheSeparatesQueriesAndAcceptHeaders()
    {
        $calls = 0;
        $adapter = $this->cachedAdapter($calls);

        $adapter->call('GET', 'v1/x/', ['oauth_token' => 'a']);
        $adapter->call('GET', 'v1/x/', ['oauth_token' => 'b']);
        $adapter->setExtended(true);
        $adapter->call('GET', 'v1/x/', ['oauth_token' => 'a']);
        $this->assertSame(3, $calls);
    }

    public function testCacheIgnoresPostMonitorAndErrors()
    {
        $calls = 0;
        $adapter = $this->cachedAdapter($calls);

        $adapter->call('POST', 'v1/x/');
        $adapter->call('POST', 'v1/x/');
        $this->assertSame(2, $calls);

        $adapter->call('GET', 'v1/monitor/phraseanet/');
        $adapter->call('GET', 'v1/monitor/phraseanet/');
        $this->assertSame(4, $calls);
    }

    private function cachedAdapter(int &$calls): GuzzleAdapter
    {
        $handler = function ($request) use (&$calls) {
            $calls++;

            return \GuzzleHttp\Promise\Create::promiseFor(new Response(200, [], $calls === 1 ? 'one' : 'other'));
        };
        $stack = HandlerStack::create($handler);
        $stack->push(new CacheMiddleware(new ArrayAdapter(), 60));

        return new GuzzleAdapter(new Client(['base_uri' => 'http://domain.tld/api/', 'handler' => $stack]));
    }
}
