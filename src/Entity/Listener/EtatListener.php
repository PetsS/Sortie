<?php

namespace App\Entity\Listener;
use App\Entity\Sortie;

class EtatListener
{

    public function postLoad(Sortie $sortie): void
    {


        $dateNow = new \DateTime();
        $dureeMinutes = $sortie->getDuree();
        $dureeSecondes = $dureeMinutes*60;
        $dureeTimeStamp = time()+$dureeSecondes;
        $conversionDuree = \DateTime::createFromFormat('U', $dureeTimeStamp);
        $debut = $sortie->getDateDebut();
        $conversionDuree->setTimestamp($debut->getTimestamp());
        $conversionDuree->add(new \DateInterval('PT' . $dureeMinutes . 'M'));

        if ($sortie->getDateLimiteInscription() >= $dateNow) {
            $sortie->setEtat('OUVERT');
        }elseif(($dateNow < $debut) & ($dateNow > $sortie->getDateLimiteInscription())){
            $sortie->setEtat('FERME');
        } elseif (($dateNow < $conversionDuree) & ($dateNow > $debut)){
            $sortie->setEtat('EN COURS');
        } elseif (($conversionDuree < $dateNow) & ($dateNow > $sortie->getDateLimiteInscription())){
            $sortie->setEtat('TERMINE');
        }
    }

    public function prePersist(Sortie $sortie) {
        $sortie->setEtat('EN COURS');
    }
}