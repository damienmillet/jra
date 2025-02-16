<?php

namespace Entities;

use Core\Auth\Role;
use Core\EntityManager;

/**
 * Class FileEntity
 */
class File extends EntityManager
{
    private int $id;
    private string $name;
    private string $path;
    private int $size;
    private string $mimeType;
    private string $type;
    private mixed $blob;
    private string $tmpName;

    /**
     * Get the temporary name of the file.
     *
     * @return string The temporary name of the file.
     */
    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    /**
     * Set the temporary name of the file.
     *
     * @param string $tmpName The temporary name of the file.
     *
     * @return self
     */
    public function setTmpName(string $tmpName): self
    {
        $this->tmpName = $tmpName;
        return $this;
    }
    /**
     * Get the type of the file.
     *
     * @return string The type of the file.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the file.
     *
     * @param string $type The type of the file.
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the blob of the file.
     *
     * @return mixed The blob of the file.
     */
    public function getBlob()
    {
        return $this->blob;
    }

    /**
     * Set the blob of the file.
     *
     * @param mixed $blob The blob of the file.
     *
     * @return self
     */
    public function setBlob($blob): self
    {
        $this->blob = $blob;
        return $this;
    }

    /**
     * Get the ID of the file.
     *
     * @return integer The ID of the file.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the ID of the file.
     *
     * @param integer $id The ID of the file.
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the name of the file.
     *
     * @return string The name of the file.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the file.
     *
     * @param string $name The name of the file.
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the path to the file.
     *
     * @return string The path to the file.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the path to the file.
     *
     * @param string $path The path to the file.
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the size of the file in bytes.
     *
     * @return integer The size of the file in bytes.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Set the size of the file in bytes.
     *
     * @param integer $size The size of the file in bytes.
     *
     * @return self
     */
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get the MIME type of the file.
     *
     * @return string The MIME type of the file.
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Set the MIME type of the file.
     *
     * @param string $mimeType The MIME type of the file.
     *
     * @return self
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }



    /**
     * Converts the file object to an array.
     *
     * @return array The file object as an array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }


    /**
     * Converts the file object to a JSON string.
     *
     * @return string The file object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
