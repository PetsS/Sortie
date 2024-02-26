<?php

namespace App\Entity\EntityListen;

class EtatListener
{

    public function postLoad(Sortie $sortie): void

    {

        if($sortie->getDateLimiteInscription() < new \DateTime()){

            $sortie->setEtat('TERMINER');

        }


    }
}