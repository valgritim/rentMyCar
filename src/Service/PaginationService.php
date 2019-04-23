<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationService{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    public function getPages(){
        //Connaitre le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData(){
        //Calculer l'offset(=le start)
        $offset = $this->currentPage * $this->limit - $this->limit;        
        //demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        //Renvoyer les éléments en question
        return $data;
    }


    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setCurrentPage($currentPage){
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }

}