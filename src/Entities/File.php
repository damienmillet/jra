<?php

/**
 * Entity file for defining the File Entity class.
 * php version 8.2
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Entities;

use Core\Auth\Role;
use Core\EntityManager;

/**
 * Class FileEntity
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class File extends EntityManager
{
    private int $_id;
    private string $_name;
    private string $_path;
    private int $_size;
    private string $_mimeType;
    private string $_type;
    private mixed $_blob;
    private string $_tmpName;

    /**
     * Get the temporary name of the file.
     *
     * @return string The temporary name of the file.
     */
    public function getTmpName(): string
    {
        return $this->_tmpName;
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
        $this->_tmpName = $tmpName;
        return $this;
    }
    /**
     * Get the type of the file.
     *
     * @return string The type of the file.
     */
    public function getType(): string
    {
        return $this->_type;
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
        $this->_type = $type;
        return $this;
    }

    /**
     * Get the blob of the file.
     *
     * @return mixed The blob of the file.
     */
    public function getBlob()
    {
        return $this->_blob;
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
        $this->_blob = $blob;
        return $this;
    }

    /**
     * Get the ID of the file.
     *
     * @return int The ID of the file.
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * Set the ID of the file.
     *
     * @param int $id The ID of the file.
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Get the name of the file.
     *
     * @return string The name of the file.
     */
    public function getName(): string
    {
        return $this->_name;
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
        $this->_name = $name;
        return $this;
    }

    /**
     * Get the path to the file.
     *
     * @return string The path to the file.
     */
    public function getPath(): string
    {
        return $this->_path;
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
        $this->_path = $path;
        return $this;
    }

    /**
     * Get the size of the file in bytes.
     *
     * @return int The size of the file in bytes.
     */
    public function getSize(): int
    {
        return $this->_size;
    }

    /**
     * Set the size of the file in bytes.
     *
     * @param int $size The size of the file in bytes.
     *
     * @return self
     */
    public function setSize(int $size): self
    {
        $this->_size = $size;
        return $this;
    }

    /**
     * Get the MIME type of the file.
     *
     * @return string The MIME type of the file.
     */
    public function getMimeType(): string
    {
        return $this->_mimeType;
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
        $this->_mimeType = $mimeType;
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
            'id'           => $this->getId(),
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
