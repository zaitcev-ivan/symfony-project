<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class PasswordResetRequestSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
    private $urlGenerator;

    public function __construct
    (
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $urlGenerator,
        $sender
    )
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::PASSWORD_RESET_REQUEST => 'onPasswordResetRequest',
        ];
    }

    /**
     * @param GenericEvent $event
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     */
    public function onPasswordResetRequest(GenericEvent $event): void
    {
        /** @var User $user */
        $user = $event->getSubject();

        $url = $this->urlGenerator->generate('password_reset_confirm', ['token' => $user->getResetToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $subject = 'Запрос на сброс пароля';
        $body = 'Перейдите по ссылке для смены пароля: ' . $url;

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body, 'text/html')
        ;

        $this->mailer->send($message);
    }
}