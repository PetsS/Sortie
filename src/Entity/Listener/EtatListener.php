<?php

namespace App\Entity\Listener;
use App\Entity\Sortie;

class EtatListener
{

    public function postLoad(): void
    {
        $sortie = getSortie();
        $dateNow = new \DateTime();
        $dureeMinutes = $sortie->getDuree();
        $dureeSecondes = $dureeMinutes*60;
        $dureeTimeStamp = time()+$dureeSecondes;

        $conversionDuree = \DateTime::createFromFormat('U', $dureeTimeStamp);

        $debut = $sortie->getDateDebut();

        dd($debut);
        $conversionDuree->setTimestamp($debut->getTimestamp());

        if ($sortie->getDateLimiteInscription() >= $dateNow) {
            $sortie->setEtat('OUVERT');
        }elseif(($sortie->getDateLimiteInscription() < $dateNow) && ($dateNow < $sortie->getDateDebut())){
            $sortie->setEtat('FERME');
//        } elseif (($sortie->getDateDebut() <= $dateNow) && ($dateNow < $conversionDuree)){
        } elseif ($dateNow > $sortie->getDateDebut()){
            $sortie->setEtat('EN COURS');
        } elseif ($conversionDuree <= $dateNow){
            $sortie->setEtat('TERMINE');
        }
    }

    public function prePersist(Sortie $sortie) {
        $sortie->setEtat('EN COURS');
    }
}