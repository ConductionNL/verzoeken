<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Request as CCRequest;

class RequestSubscriber implements EventSubscriberInterface
{
    private $params;
    private $em;
    private $serializer;
    private $nlxLogService;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->params = $params;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
                KernelEvents::VIEW => ['newRequest', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function newRequest(GetResponseForControllerResultEvent $event)
    {
    	$result = $event->getControllerResult();
    	$method = $event->getRequest()->getMethod();
    	$route = $event->getRequest()->attributes->get('_route');

    	if(!$result instanceof CCRequest || $route != 'api_requests_post_collection'){
    		//var_dump('a');
    		return;
    	}

    	if(!$result->getReference()){
            $organization = json_decode($event->getRequest()->getContent(),true)['organization'];
    		$referenceId = $this->em->getRepository('App\Entity\Request')->getNextReferenceId($organization);
    		$result->setReferenceId($referenceId);
    		$result->setReference('UT'.'-'.date('Y').'-'.$referenceId);
    	}

        return $result;
    }

}
