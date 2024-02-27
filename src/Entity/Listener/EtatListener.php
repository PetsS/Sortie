<?php

namespace App\Entity\Listener;

use App\Entity\Sortie;

class EtatListener
{
    public function postLoad(Sortie $sortie): void
    {
     if($sortie->getDateLimiteInscription() < new \DateTime()) {
         $sortie->setEtat('TERMINER');
     }



    }



}