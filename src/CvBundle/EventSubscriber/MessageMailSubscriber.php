<?php
namespace CvBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use CvBundle\Entity\Message;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class MessageMailSubscriber implements EventSubscriberInterface
{

    private $mailer;
    /**
     * @var Container
     */
    private $container;

    public function __construct(\Swift_Mailer $mailer, Container $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['sendMail', EventPriorities::POST_WRITE]],
        ];
    }

    public function sendMail(GetResponseForControllerResultEvent $event)
    {
        $message = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$message instanceof Message || Request::METHOD_POST !== $method) {
            return;
        }

        $mail = \Swift_Message::newInstance()
            ->setSubject('[CV] '.$message->getSubject())
            ->setFrom($message->getEmail())
            ->setTo($this->container->getParameter('user_mail'))
            ->setBody(sprintf('%s', $message->getContent()));

        $this->mailer->send($mail);
    }
}