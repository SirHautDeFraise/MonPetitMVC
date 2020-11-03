<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace APP\Controller;

use APP\Model\GestionClientModel;
use ReflectionClass;
use Exception;
use Tools\MyTwig;
use Tools\Repository;
use APP\Entity\Client;

class GestionClientController {

    public function chercheUn(array $params): void {
        $repository = Repository::getRepository("APP\Entity\Client");
        $ids = $repository->findIds();
        $params['lesId'] = $ids;
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $unClient = $repository->find($id);
            $params['unClient'] = $unClient;
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/unClient.html.twig";
        MyTwig::afficheVue($vue, $params);
    }

    public function chercheTous() {
        $repository = Repository::getRepository("APP\Entity\Client");
        $clients = $repository->FindAll();
        if ($clients) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "/plsClients.html.twig";
            MyTwig::afficheVue($vue, array('clients' => $clients));
        } else {
            throw new Exception("Aucun client à afficher");
        }
    }

    public function creerClient(array $params) {
        if (empty($params)) {
            $vue = "GestionClientView\\creerClient.html.twig";
            MyTwig::afficheVue($vue, array());
        } else {
            $client = new Client($params);
            $repository = Repository::getRepository("APP\Entity\Client");
            $repository->insert($client);
            $this->chercheTous();
        }
    }

    public function enregistreClient(array $params) {
        $client = new Client($params);
        $modele = new GestionClientModel();
        $modele->enregistreClient($client);
    }

    public function testFindBy($params) {
        $repository = Repository::getRepository("APP\Entity\Client");
        $params = array("titreCli" => "Monsieur", "villeCli" => "Toulon");
        $clients = $repository->findBytitreCli_and_villeCli($params);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/tousClients.html.twig";
        MyTwig::afficheVue($vue, array('clients' => $clients));
    }

}
