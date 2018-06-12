<?php
/**
 * Photo upload listener.
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Photo;
use AppBundle\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PhotoUploadListener.
 */
class PhotoUploadListener
{
    /**
     * Uploader service.
     *
     * @var null|\AppBundle\Service\FileUploader $uploaderService
     */
    protected $uploaderService = null;

    /**
     * PhotoUploadListener constructor.
     *
     * @param \AppBundle\Service\FileUploader $fileUploader File uploader service
     */
    public function __construct(FileUploader $fileUploader)
    {
        $this->uploaderService = $fileUploader;
    }

    /**
     * Pre persist.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update.
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args Event args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Post load (after load entity from database).
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Photo) {
            return;
        }

        if ($fileName = $entity->getPhoto()) {
            $entity->setPhoto(new File($this->uploaderService->getTargetDir().'/'.$fileName));
        }
    }

    /**
     * Upload file.
     *
     * @param \AppBundle\Entity\Photo $entity Photo entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getPhoto();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploaderService->upload($file);
        $entity->setPhoto($fileName);
    }
}
