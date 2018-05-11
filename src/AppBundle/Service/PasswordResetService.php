<?php

namespace AppBundle\Service;

use AppBundle\Dto\PasswordResetDto;
use AppBundle\Dto\PasswordResetRequestDto;
use AppBundle\Events;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Сервис для работы со сбросом пароля пользователя
 * Class PasswordResetService
 * @package AppBundle\Service
 */
class PasswordResetService
{
    private const INTERVAL_EXPIRES_DAYS = 3;

    private $em;
    /** @var UserRepository */
    private $userRepository;
    /** @var EventDispatcherInterface  */
    private $eventDispatcher;

    public function __construct
    (
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository('AppBundle:User');
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Отправка запроса на сброс пароля
     * @param PasswordResetRequestDto $dto
     * @throws \Exception
     * @throws \DomainException
     */
    public function request(PasswordResetRequestDto $dto): void
    {
        if(!$user = $this->userRepository->findOneByEmail($dto->email)) {
            throw new \DomainException('Данный email не зарегистрирован');
        }

        $user->generateResetToken(new \DateInterval('P'.self::INTERVAL_EXPIRES_DAYS.'D'));
        $this->em->persist($user);
        $this->em->flush();

        $event = new GenericEvent($user);
        $this->eventDispatcher->dispatch(Events::PASSWORD_RESET_REQUEST, $event);
    }

    /**
     * Валидация токена сброса пароля
     * @param $token
     * @throws \DomainException
     */
    public function validateToken($token): void
    {
        if (empty($token) || !\is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if(!$user = $this->userRepository->findOneByResetToken($token)) {
            throw new \DomainException('Password reset token not found.');
        }
        if(!$user->isResetTokenValid($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    /**
     * Сброс пароля
     * @param $token
     * @param PasswordResetDto $dto
     * @throws \DomainException
     */
    public function resetPassword($token, PasswordResetDto $dto): void
    {
        $user = $this->userRepository->findOneByResetToken($token);
        $user->resetPassword($dto->plainPassword);
        $this->em->persist($user);
        $this->em->flush();
    }
}