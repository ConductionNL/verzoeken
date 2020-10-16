<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Request as CCRequest;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class RequestSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['newRequest', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function newRequest(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $route = $event->getRequest()->attributes->get('_route');

        if (!$result instanceof CCRequest || $route != 'api_requests_post_collection') {
            //var_dump('a');
            return;
        }

        if (!$result->getReference()) {
            // Lets get a shortcode
            $organization = json_decode($event->getRequest()->getContent(), true)['organization'];
            $organization = $this->commonGroundService->getResource($organization);

            if (array_key_exists('shortcode', $organization) && $organization['shortcode'] != null) {
                $shortcode = $organization['shortcode'];
            } else {
                $shortcode = $organization['name'];
            }

            // Lets get a reference id
            $referenceId = $this->em->getRepository('App\Entity\Request')->getLastReferenceId($organization['@id']);

            // Turn that into a reference and check for double references
            $double = true;
            while ($double) {
                $referenceId++;
                $reference = $shortcode.'-'.date('Y').'-'.$referenceId;
                $double = $this->em->getRepository('App\Entity\Request')->findOneBy(['reference' => $reference]);
            }

            $result->setReferenceId($referenceId);
            $result->setReference($reference);
        }

        return $result;
    }
}
