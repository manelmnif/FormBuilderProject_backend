<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\ElementType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $admin_user = new User();
        $admin_user->setEmail('manel.mnif@esprit.tn');
        $admin_user->setPassword($this->passwordEncoder->encodePassword(
            $admin_user,
            'manel'
        ));
        $admin_user->setRoles(array('ROLE_SUPER_ADMIN'));
        $admin_user->setUsername(('manelmnif'));
        $manager->persist($admin_user);



        $manager->flush();

        $elementType1 = new ElementType();
        $elementType1->setType('Text Field');
        $elementType1->setMultiple(false);
        $manager->persist($elementType1);
        $manager->flush();
        
        $elementType2 = new ElementType();
        $elementType2->setType('Text Area');
        $elementType2->setMultiple(false);
        $manager->persist($elementType2);
        $manager->flush();
        
        $elementType3 = new ElementType();
        $elementType3->setType('Number');
        $elementType3->setMultiple(false);
        $manager->persist($elementType3);
        $manager->flush();

        $elementType4 = new ElementType();
        $elementType4->setType('Date');
        $elementType4->setMultiple(false);
        $manager->persist($elementType4);
        $manager->flush();
    }

    public function loadElementType(ObjectManager $manager)
    {
        $elementType = new ElementType();
        $elementType->setType('Text Field');
        $elementType->setMultiple(false);
    
      
        $manager->persist($elementType);
        $manager->flush();
    }
}