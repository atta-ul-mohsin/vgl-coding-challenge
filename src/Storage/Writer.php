<?php declare(strict_types=1);

namespace App\Storage;

class Writer
{
    /**
     * @var string
     */
    private $storagePath;

    /**
     * @param string|null $storagePath
     */
    public function __construct(string $storagePath = null)
    {
        $this->storagePath = $storagePath ?? __DIR__ . '/../../storage/';
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function create(string $key, string $value) : void
    {
        $fileName = $this->createFileName($key);

        if (file_exists($fileName) === true) {
            throw new \RuntimeException('File with key already exists: ' . $key);
        }

        file_put_contents($fileName, $value);
    }

    /**
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $fileName = $this->createFileName($key);

        if (file_exists($fileName) === false) {
            return; // File doesn't exist, return without any errors
        }

        unlink($fileName);
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function update(string $key, string $value) : void
    {
        $fileName = $this->createFileName($key);

        if (file_exists($fileName) === false) {
            throw new \RuntimeException('File with key does not exist: ' . $key);
        }

        file_put_contents($fileName, $value);
    }

    /**
     * @param string $key
     * @return string
     */
    private function createFileName(string $key) : string
    {
        return $this->storagePath . $key;
    }
}
