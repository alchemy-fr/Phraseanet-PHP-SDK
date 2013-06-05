<?php

namespace PhraseanetSDK\Tests\Recorder\Storage;

use PhraseanetSDK\Recorder\Storage\FilesystemStorage;

class FilesystemStorageTest extends \PHPUnit_Framework_TestCase
{
    private $file;

    public function setUp()
    {
        $this->file = __DIR__ . '/filesystem-test.json';
    }

    public function tearDown()
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }

        parent::tearDown();
    }

    public function testFetch()
    {
        $data = array('hello' => 'world');
        file_put_contents($this->file, json_encode($data));
        $storage = new FilesystemStorage($this->file);
        $this->assertSame($data, $storage->fetch());
    }

    public function testFetchInvalidPath()
    {
        $storage = new FilesystemStorage('/path/to/dir/file.unknown-extension');
        $this->assertSame(array(), $storage->fetch());
    }

    public function testFetchInvalidData()
    {
        file_put_contents($this->file, 'hello world !');
        $storage = new FilesystemStorage($this->file);
        $this->assertSame(array(), $storage->fetch());
    }

    public function testSave()
    {
        $storage = new FilesystemStorage($this->file);
        $storage->save(array('hello' => 'world'));
        $json = '{
    "hello": "world"
}';
        $this->assertEquals($json, file_get_contents($this->file));
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testSaveInvalidPath()
    {
        $storage = new FilesystemStorage('/path/to/dir/file.unknown-extension');
        $storage->save(array('hello' => 'world'));
    }
}
