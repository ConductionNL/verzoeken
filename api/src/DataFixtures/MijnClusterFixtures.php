<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Submitter;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MijnClusterFixtures extends Fixture
{
    private $commonGroundService;
    private $params;

    public function __construct(CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $this->commonGroundService = $commonGroundService;
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {
        return false;
        // Lets make sure we only run these fixtures on larping enviroment
        if ($this->params->get('app_domain') != 'mijncluster.nl' && strpos($this->params->get('app_domain'), 'mijncluster.nl') == false) {
            return false;
        }

        $now = new \Datetime();

        $id = Uuid::fromString('456918bc-8419-4e54-90eb-bafd3d18c6ff');
        $request = new Request();
        $request->setOrganization($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'organizations', 'id'=>'cc935415-a674-4235-b99d-0c7bfce5c7aa']));
        $request->setRequestType($this->commonGroundService->cleanUrl(['component'=>'wrc', 'type'=>'request_types', 'id'=>'23d4803a-67cd-4720-82d0-e1e0a776d8c4']));
        $request->setStatus('submited');
        $request->setDateSubmitted($now);
        $request->setProperties(
            [
                'datum'       => $now->format('Y-m-d H:i:s'),
                'adres'       => 'https://bag.basisregistraties.overheid.nl/api/v1/nummeraanduidingen/0796200000306540',
                'wie'         => "['".$this->commonGroundService->cleanUrl(['component'=>'brp', 'type'=>'ingeschrevenpersoon', 'id'=>'201445906'])."']",
                'wie_bsn'     => "['201445906']",
                'email'       => 'verhuizen@conduction.nl',
                'telefoon'    => '0612345678',
                'notificatie' => false,
            ]
        );

        $manager->persist($request);
        $request->setId($id);
        $manager->persist($request);
        $manager->flush();
        $request = $manager->getRepository('App:Request')->findOneBy(['id' => $id]);

        $sumitter = new Submitter();
        $sumitter->setRequest($request);
        $request->addSubmitter($sumitter);
        $sumitter->setBrp($this->commonGroundService->cleanUrl(['component'=>'brp', 'type'=>'ingeschrevenpersoon', 'id'=>'201445906']));
        $sumitter->setPerson($this->commonGroundService->cleanUrl(['component'=>'brp', 'type'=>'ingeschrevenpersoon', 'id'=>'201445906']));
        $manager->persist($sumitter);

        $manager->flush();
    }
}
