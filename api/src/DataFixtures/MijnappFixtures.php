<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Submitter;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MijnappFixtures extends Fixture
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
        // Lets make sure we only run these fixtures on larping enviroment
        if (
            $this->params->get('app_domain') != 'mijnapp.zaakonline.nl' && strpos($this->params->get('app_domain'), 'mijnapp.zaakonline.nl') == false &&
            $this->params->get('app_domain') != 'verhuizen.accp.s-hertogenbosch.nl' && strpos($this->params->get('app_domain'), 'verhuizen.accp.s-hertogenbosch.nl') == false &&
            $this->params->get('app_domain') != 'shertogenbosch.commonground.nu' && strpos($this->params->get('app_domain'), 'shertogenbosch.commonground.nu') == false
        ) {
            return false;
        }

        $now = new \Datetime();

        $i = 1;
        while ($i <= 10) {
            $request = new Request();
            $request->setOrganization($this->commonGroundService->cleanUrl(["component"=>"wrc","type"=>"organizations","id"=>"cc935415-a674-4235-b99d-0c7bfce5c7aa"]));
            $request->setRequestType($this->commonGroundService->cleanUrl(["component"=>"wrc","type"=>"request_types","id"=>"23d4803a-67cd-4720-82d0-e1e0a776d8c4"]));
            $request->setStatus('submited');
            $request->setDateSubmitted($now);
            $request->setProperties(
                [
                    'datum'       => $now->format('Y-m-d H:i:s'),
                    'adres'       => 'https://bag.basisregistraties.overheid.nl/api/v1/nummeraanduidingen/0796200000306540',
                    'wie'         => "['{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906',{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445907']",
                    'email'       => 'verhuizen@conduction.nl',
                    'telefoon'    => '0612345678',
                    'notificatie' => false,
                ]
            );

            $manager->persist($request);

            $sumitter = new Submitter();
            $sumitter->setRequest($request);
            $request->addSubmitter($sumitter);
            $sumitter->setBrp("{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906");
            $sumitter->setPerson("{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906");
            $manager->persist($sumitter);

            $i++;
        }

        $manager->flush();
    }
}
