<?php


namespace AppBundle\Doctrine;


use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var User $entity */
        $entity = $args->getEntity();
        if(!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /** @var User $entity */
        $entity = $args->getEntity();
        if(!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents()
    {
       return ['prePersist', 'preUpdate'];
    }

    /**
     * @param User $entity
     */
    public function encodePassword(User $entity): void
    {
        $plainPassword = $entity->getPlainPassword();
        if(null === $plainPassword) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }
}