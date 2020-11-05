<?php

namespace App\DataFixtures;

use App\Entity\Ecole\Classe;
use App\Entity\Ecole\Enseignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Exception;

class EcoleFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadClasse($manager,
            "PS",
            0,
            "maternelle",
            'PS.png',
            'user1'
        );
        $this->loadClasse($manager,
            "MS",
            1,
            "maternelle",
            "MS.png",
            'user2'
        );
        $this->loadClasse($manager,
            "GS",
            2,
            "maternelle",
            "GS.png",
            'user3'
        );
        $this->loadClasse($manager,
            "CP",
            3,
            "primaire",
            "CP.jpg",
            'user4'
        );
        $this->loadClasse($manager,
            "CE1",
            4,
            "primaire",
            "CE1.jpg",
            'user5'
        );
        $this->loadClasse($manager,
            "CE2",
            5,
            "primaire",
            "CE2.jpg",
            'user6'
        );
        $this->loadClasse($manager,
            "CM1",
            6,
            "primaire",
            "CM1.jpg",
            'user7'
        );
        $this->loadClasse($manager,
            "CM2",
            7,
            "primaire",
            "CM2.jpg",
            'user8'
        );

        $manager->flush();
    }

    public function loadClasse($manager, $titre, $position, $niveau, $img, $userRef){
        $classe = new Classe();
        $classe->setTitre($titre);
        $classe->setPosition($position);
        $classe->setNiveau($niveau);
        $classe->addEnseignant($this->getReference("user_".$userRef));
        $manager->persist($classe);
        $manager->flush();

        if($img !== ''){
            try {
                $classe->setVignetteFile(new UploadedFile('public/medias/fixtures/' . $img, $img, null, null, true), false);
                $classe->uploadVignetteImg(true);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return 10;
    }
}
