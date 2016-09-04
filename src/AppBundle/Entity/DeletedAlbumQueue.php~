<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name=DeletedAlbumQueue::TABLE_NAME,
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="album_id", columns={"album_id"})
 *     }
 * )
 * @HasLifecycleCallbacks
 */
class DeletedAlbumQueue
{
    const TABLE_NAME = 'deleted_albums_queue';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $albumId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\PrePersist()
     */
    public function initDefaultValues()
    {
        $this->addedAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set albumId
     *
     * @param integer $albumId
     *
     * @return DeletedAlbumQueue
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;

        return $this;
    }

    /**
     * Get albumId
     *
     * @return integer
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     *
     * @return DeletedAlbumQueue
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addetAt
     *
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }
}
