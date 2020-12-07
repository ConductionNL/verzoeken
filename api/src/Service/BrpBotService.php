<?php

// Conduction/CommonGroundBundle/Service/RequestTypeService.php

/*
 * This file is part of the Conduction Common Ground Bundle
 *
 * (c) Conduction <info@conduction.nl>
 *
 * The main focus of this service to to provide craweler bot behavior for the BRP in order to make up for the uther and complete lack of proper data connections
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BrpBotService
{
    private $em;
    private $params;
    private $cash;
    private $client;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params, CacheInterface $cache)
    {
        $this->em = $em;
        $this->params = $params;
        $this->cash = $cache;

        $this->client = new Client([
            // Base URI is used with relative requests
            /*@todo we need to make this configurable */
            //'base_uri' => $this->params->get('common_ground.bag.location'),
            'base_uri' => 'http://brp.zaakonline.nl/',
            // You can set any number of default request options.
            'timeout'  => 4000.0,
            // This api key needs to go into params
            'headers' => ['X-Api-Key' => $this->params->get('common_ground.bag.apikey')],
        ]);
    }
}
