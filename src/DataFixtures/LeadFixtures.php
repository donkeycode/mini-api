<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Lead;

class LeadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lead = new Lead();
        $lead->setFirstname('Bob');
        $lead->setLastname('lastname');
        $lead->setEmail('bob@lastname.fr');
        $lead->setBirthdate(new \DateTime());
        $manager->persist($lead);

        $manager->flush();
    }
}
