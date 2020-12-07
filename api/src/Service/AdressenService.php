<?php

// Conduction/CommonGroundBundle/Service/KadasterService.php

/*
 * This file is part of the Conduction Common Ground Bundle
 *
 * (c) Conduction <info@conduction.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdressenService
{
    private $params;
    private $cash;
    private $client;

    public function __construct(ParameterBagInterface $params, CacheInterface $cache)
    {
        $this->params = $params;
        $this->cash = $cache;

        $this->client = new Client([
            // Base URI is used with relative requests
            //'base_uri' => $this->params->get('common_ground.bag.location'),
            'base_uri' => 'http://adressen.zaakonline.nl/',
            // You can set any number of default request options.
            'timeout'  => 4000.0,
            // This api key needs to go into params
            'headers' => ['X-Api-Key' => $this->params->get('common_ground.bag.apikey')],
        ]);
    }

    public function getNummeraanduiding($postcode, $huisnummer, $huisnummerToevoeging)
    {
        // Lets first try the cach
        $item = $this->cash->getItem('nummeraanduiding_'.md5($postcode.$huisnummer.$huisnummerToevoeging));
        if ($item->isHit()) {
            return $item->get();
        }

        $response = $this->client->request('GET', '/adressen', ['query' => 'postcode=$postcode&huisnummer=$huisnummer&huisnummer_toevoeging=$huisnummerToevoeging']);
        $response = json_decode($response->getBody(), true);
        $response = $response;
        $item->set($response);
        $item->expiresAt(new \DateTime('tomorrow'));
        $this->cash->save($item);

        return $item->get();
    }
}
