<?php

namespace AppBundle\Service;

use AppBundle\Dto\ContactCreateDto;
use AppBundle\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private $em;

    public function __construct
    (
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * Создание сообщения обратной связи
     * @param ContactCreateDto $dto
     */
    public function create(ContactCreateDto $dto): void
    {
        $contact = new Contact();
        $contact->setName($dto->name);
        $contact->setEmail($dto->email);
        $contact->setSubject($dto->subject);
        $contact->setMessage($dto->message);

        $this->em->persist($contact);
        $this->em->flush();
    }
}