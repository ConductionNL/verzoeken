<?php

namespace App\DataFixtures;

use App\Entity\Geboorte;
use App\Entity\Ingeschrevenpersoon;
use App\Entity\Kind;
use App\Entity\NaamPersoon;
use App\Entity\Ouder;
use App\Entity\Partner;
use App\Entity\Verblijfplaats;
use App\Entity\Waardetabel;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       
    }
    
    
}
