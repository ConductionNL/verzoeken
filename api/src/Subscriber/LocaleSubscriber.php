<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Request;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $params;
    private $em;
    private $serializer;
    private $commonGroundService;
    private $nlxLogService;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, SerializerInterface $serializer, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->commonGroundService = $commonGroundService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['locale', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function locale(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $contentType = $event->getRequest()->headers->get('accept');
        $method = $event->getRequest()->getMethod();
        $route = $event->getRequest()->attributes->get('_route');

        if (!$contentType) {
            $contentType = $event->getRequest()->headers->get('Accept');
        }

        if (!$event->getRequest()->query->get('_locale') == 'nl' || !$result instanceof Request) {
            return;
        }

        switch ($contentType) {
            case 'application/json':
                $renderType = 'json';
                break;
            case 'application/ld+json':
                $renderType = 'jsonld';
                break;
            case 'application/hal+json':
                $renderType = 'jsonhal';
                break;
            default:
                $contentType = 'application/json';
                $renderType = 'json';
        }

        $response = $this->serializer->serialize(
            $result,
            $renderType,
            ['enable_max_depth'=> true, 'datetime_timezone'=> 'Europe/Amsterdam']
        );
        $response = new Response(
            $response,
            Response::HTTP_OK,
            ['content-type' => $contentType]
        );

        $event->setResponse($response);
    }
}
