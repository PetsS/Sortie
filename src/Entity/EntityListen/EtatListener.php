<?php

namespace App\Entity\EntityListen;

use Doctrine\ORM\EntityManagerInterface;

class EtatListener
{

    public function postLoad(Sortie $sortie): void
    {
        if($sortie->getDateLimiteInscription() < new \DateTime()){
            $sortie->setEtat('TERMINE');
        }
    }

    public function prePersist(Sortie $sortie) {
        $sortie->setEtat('EN COURS');
    }
}