<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use App\Form\CategorieSupprimerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    #[Route('/chaton/ajouter', name: 'ajouter_chaton')]
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request): Response
    {
        $chaton = new Chaton();
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            //on demande à l'entityManager de sauvegarder notre objet
            $em->persist($chaton);
            //on demande à l'entityManager d'exécuter les requêtes
            $em->flush();
            return $this->redirectToRoute("categorie_chaton", ["id"=>$chaton->getCategorie()->getId()]);
        }

            return $this->render('chaton/index.html.twig', [
            'formulaire' => $form->createView()
            ]);
    }


    #[Route('/chaton/{id}', name: 'categorie_chaton')]
    public function categorieChaton($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Aucune catégorie avec l\'id ' . $id);
        }

        $chatons = $categorie->getChatons();

        return $this->render('chaton/chatonCategorie.html.twig', [
            'controller_name' => 'ChatonsController',
            'categorie' => $categorie,
            'chatons' => $chatons
        ]);
    }

    #[Route('/chaton/modifier/{id}', name: 'modifier_chaton')]
    public function modifierChaton($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chaton) {
            throw $this->createNotFoundException('Aucun chaton avec l\'id ' . $id);
        }

        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            //on demande à l'entityManager de sauvegarder notre objet
            $em->persist($chaton);
            //on demande à l'entityManager d'exécuter les requêtes
            $em->flush();
            return $this->redirectToRoute("categorie_chaton", ["id"=>$chaton->getCategorie()->getId()]);
        }

        return $this->render('chaton/modifier.html.twig', [
            'controller_name' => 'ChatonsController',
            'chaton' => $chaton,
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/chaton/supprimer/{id}', name: 'supprimer_chaton')]
    public function supprimerChaton($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chaton) {
            throw $this->createNotFoundException('Aucun chaton avec l\'id ' . $id);
        }

        $form = $this->createForm(ChatonSupprimerType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            //on demande à l'entityManager de sauvegarder notre objet
            $em->remove($chaton);
            //on demande à l'entityManager d'exécuter les requêtes
            $em->flush();
            return $this->redirectToRoute("categorie_chaton", ["id"=>$chaton->getCategorie()->getId()]);
        }

        return $this->render('chaton/supprimer.html.twig', [
            'controller_name' => 'ChatonsController',
            'chaton' => $chaton,
            'formulaire' => $form->createView(),
        ]);
    }
}
