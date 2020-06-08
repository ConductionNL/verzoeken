<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Submitter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {
        // Lets make sure we only run these fixtures on larping enviroment
        if ($this->params->get('app_domain') != "mijncluster.nl" && strpos($this->params->get('app_domain'), "mijncluster.nl") == false) {
            return false;
        }

        $now = New \Datetime;

        $id = Uuid::fromString('456918bc-8419-4e54-90eb-bafd3d18c6ff');
        $request = new Request();
        $request->setOrganization("https://wrc.mijncluster.nl/organizations/cc935415-a674-4235-b99d-0c7bfce5c7aa");
        $request->setRequestType("https://vtc.mijncluster.nl/request_types/23d4803a-67cd-4720-82d0-e1e0a776d8c4");
        $request->setStatus("submited");
        $request->setDateSubmitted($now);
        $request->setProperties(
            [
                "datum" =>$now->format('Y-m-d H:i:s'),
                "adres" =>"Een willekeurig bag adres",
                "persoon" =>"Een willekeurig bsn"
            ]
        );


        $manager->persist($request);
        $request->setId($id);
        $manager->persist($request);
        $manager->flush();
        $request = $manager->getRepository('App:Request')->findOneBy(['id' => $id]);

        $sumitter = New Submitter();
        $sumitter->setRequest($request);
        $request->addSubmitter($sumitter);
        $sumitter->setBrp("https://brp.mijncluster.nl/organizations/cc935415-a674-4235-b99d-0c7bfce5c7aa");
        $sumitter->setPerson("https://cc.mijncluster.nl/organizations/cc935415-a674-4235-b99d-0c7bfce5c7aa");
        $manager->persist($sumitter);

        $manager->flush();
    }


}
