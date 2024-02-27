<?php

namespace App\Entity\EntityListen;

use App\Entity\Sortie;

class EtatListener
{


    public function postLoad(Sortie $sortie): void
     {
         if ($sortie->getDateLimiteInscription() < new \DateTime()) {
             $sortie->setEtat('TERMINER');
         } else {

         }
     }

      public function prePersist (Sortie $sortie) {
          $sortie->setEtat('EN ATTENTE');

      }

}