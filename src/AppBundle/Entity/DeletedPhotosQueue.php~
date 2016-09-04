<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name=DeletedPhotosQueue::TABLE_NAME,
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="photo_id", columns={"photo_id"})
 *     }
 * )
 * @HasLifecycleCallbacks
 */
class DeletedPhotosQueue
{
    const TABLE_NAME = 'deleted_photos_queue';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $photoId;

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
     * Set addedAt
     *
     * @param \DateTime $addedAt
     *
     * @return DeletedPhotosQueue
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addedAt
     *
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set photoId
     *
     * @param integer $photoId
     *
     * @return DeletedPhotosQueue
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return integer
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }
}
