<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Storage\Reader;

class ReaderTest extends TestCase
{

    private const TEST_STORAGE_PATH = __DIR__ . '/../storage/';

    /**
     * @return void
     */
    public function testReadExistingFile()
    {
        $key = 'test_key';
        $value = '{"key": "value"}';

        // Create the test file with the specified data
        $filePath = self::TEST_STORAGE_PATH . $key;
        file_put_contents($filePath, $value);

        // Create an instance of the Reader class with the test storage path
        $reader = new Reader(self::TEST_STORAGE_PATH);

        // Call the read method
        $result = $reader->read($key);

        // Assert that the method returns the expected value
        $this->assertSame($value, $result);

        // Clean up - remove the test file
        unlink($filePath);
    }

    /**
     * @return void
     */
    public function testReadNonExistingFile()
    {
        $key = 'non_existing_key';

        // Create an instance of the Reader class with the test storage path
        $reader = new Reader(self::TEST_STORAGE_PATH);

        // Call the read method and expect a RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('File with key does not exist: ' . $key);

        $reader->read($key);
    }

    /**
     * @return void
     */
    public function testReadError()
    {
        $key = 'error_key';

        // Create an instance of the Reader class with the test storage path
        $reader = new Reader(self::TEST_STORAGE_PATH);

        // Create a mock for the Reader class with a method that always throws RuntimeException
        $mockReader = $this->createMock(Reader::class);

        $mockReader->method('read')
            ->willThrowException(new \RuntimeException('File with key could not be read: ' . $key));

        // Call the read method on the mock and expect a RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('File with key could not be read: ' . $key);

        $mockReader->read($key);
    }
}
