<?php

namespace App\Entity\Listener;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;

class EtatListener
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

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

        if ($sortie->isIsSortieValidee()) {
            if ($sortie->getDateLimiteInscription() >= $dateNow) {
                $sortie->setEtat('OUVERT');
            } elseif (($dateNow < $debut) & ($dateNow > $sortie->getDateLimiteInscription())) {
                $sortie->setEtat('FERME');
            } elseif (($dateNow < $conversionDuree) & ($dateNow > $debut)) {
                $sortie->setEtat('EN COURS');
            } elseif (($conversionDuree < $dateNow) & ($dateNow > $sortie->getDateLimiteInscription())) {
                $sortie->setEtat('TERMINE');

            }
            elseif(count($sortie->getParticipants()) >= $sortie->getNbMaxInscription()) {
                $sortie->setEtat('COMPLET');
            }

            $this->entityManager->persist($sortie);
            $this->entityManager->flush();
            }


    }

    public function prePersist(Sortie $sortie) {
        $sortie->setEtat('EN ATTENTE');
    }
}