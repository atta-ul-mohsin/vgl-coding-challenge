<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Storage\Writer;


class WriterTest extends TestCase
{
    private const TEST_STORAGE_PATH = __DIR__ . '/../storage/';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create the storage directory in the virtual file system
        if (!is_dir(self::TEST_STORAGE_PATH)) {
            mkdir(self::TEST_STORAGE_PATH, 0777, true);
        }
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $key = 'test_key';
        $value = 'test_value';

        // Create an instance of the Writer class with the test storage path
        $writer = new Writer(self::TEST_STORAGE_PATH);

        // Call the create method
        $writer->create($key, $value);

        // Assert that the file has been created and contains the correct value
        $this->assertFileExists(self::TEST_STORAGE_PATH . $key);
        $this->assertEquals($value, file_get_contents(self::TEST_STORAGE_PATH . $key));
    }

    /**
     * @return void
     */
    public function testCreateExistingFile()
    {
        $key = 'test_key';
        $value = 'test_value';

        $writer = new Writer(self::TEST_STORAGE_PATH);

        // Create the file with the same key before testing
        file_put_contents(self::TEST_STORAGE_PATH . $key, 'existing_data');

        // Call the create method and expect a RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('File with key already exists: ' . $key);
        $writer->create($key, $value);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $key = 'test_key';
        $value = 'test_value';

        $writer = new Writer(self::TEST_STORAGE_PATH);

        // Create the file before testing
        file_put_contents(self::TEST_STORAGE_PATH . $key, $value);

        // Call the delete method
        $writer->delete($key);

        // Assert that the file has been deleted
        $this->assertFileDoesNotExist(self::TEST_STORAGE_PATH . $key);
    }

    /**
     * @return void
     */
    public function testDeleteNonExistingFile()
    {
        $key = 'test_key';

        // Create an instance of the Writer class with the test storage path
        $writer = new Writer(self::TEST_STORAGE_PATH);

        try {
            // Call the delete method and expect a RuntimeException
            $writer->delete($key);

            // If no exception is thrown, mark the test as passed
            $this->assertTrue(true);
        } catch (\RuntimeException $exception) {
            // If a RuntimeException is caught, mark the test as failed
            $this->fail('Unexpected RuntimeException: ' . $exception->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testUpdate()
    {
        $key = 'test_key';
        $value = 'test_value';

        $writer = new Writer(self::TEST_STORAGE_PATH);

        // Create the file before testing
        file_put_contents(self::TEST_STORAGE_PATH . $key, 'existing_data');

        // Call the update method
        $writer->update($key, $value);

        // Assert that the file has been updated and contains the correct value
        $this->assertFileExists(self::TEST_STORAGE_PATH . $key);
        $this->assertEquals($value, file_get_contents(self::TEST_STORAGE_PATH . $key));
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingFile()
    {
        $key = 'test_key';
        $value = 'test_value';

        // Create an instance of the Writer class with the test storage path
        $writer = new Writer(self::TEST_STORAGE_PATH);

        // Call the update method
        $writer->update($key, $value);

        // Assert that the file has been created and contains the correct value
        $this->assertFileExists(self::TEST_STORAGE_PATH . $key);
        $this->assertEquals($value, file_get_contents(self::TEST_STORAGE_PATH . $key));
    }
}
