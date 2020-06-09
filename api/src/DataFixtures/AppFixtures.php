<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Submitter;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
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
        $now = new \Datetime();

        $id = Uuid::fromString('456918bc-8419-4e54-90eb-bafd3d18c6ff');
        $request = new Request();
        $request->setOrganization("{$this->commonGroundService->getComponent('wrc')['location']}['organizations']}/templates/cc935415-a674-4235-b99d-0c7bfce5c7aa");
        $request->setRequestType("{$this->commonGroundService->getComponent('vtc')['location']}/templates//23d4803a-67cd-4720-82d0-e1e0a776d8c4");
        $request->setStatus('submited');
        $request->setReference('example-01');
        $request->setReferenceId(1);
        $request->setDateSubmitted($now);
        $request->setProperties(
            [
                'datum'   => $now->format('Y-m-d H:i:s'),
                'adres'   => 'Een willekeurig bag adres',
                'persoon' => "{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906",
                'bsn'     => '201445906',
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
        $sumitter->setBrp("{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906");
        $sumitter->setPerson("{$this->commonGroundService->getComponent('brp')['location']}/ingeschrevenpersoon/201445906");
        $manager->persist($sumitter);

        $manager->flush();
    }
}
