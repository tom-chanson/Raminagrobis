<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    #[Route('/proprietaire', name: 'proprietaire')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $doctrine->getManager();
            //on demande à l'entityManager de sauvegarder notre objet
            $em->persist($proprietaire);
            //on demande à l'entityManager d'exécuter les requêtes
            $em->flush();
            return $this->redirectToRoute("proprietaire");
        }

        $proprietaires = $doctrine->getRepository(Proprietaire::class)->findAll();

        return $this->render('proprietaire/index.html.twig', [
            'proprietaire' => $proprietaires,
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/proprietaire/supprimer/{id}', name: 'proprietaire_supprimer')]
    public function supprimerProprietaire($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException(
                'Aucun proprietaire trouvé pour cet id : '.$id
            );
        }
        $form = $this->createForm(ProprietaireSupprimerType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($proprietaire);
            $em->flush();
            return $this->redirectToRoute("proprietaire");
        }
        return $this->render('proprietaire/supprimer.html.twig', [
            'formulaire' => $form->createView(),
            'proprietaire' => $proprietaire
        ]);
    }

    #[Route('/proprietaire/modifier/{id}', name: 'proprietaire_modifier')]
    public function moddiferProprietaire($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException(
                'Aucun proprietaire trouvé pour cet id : '.$id
            );
        }
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();
            return $this->redirectToRoute("proprietaire");
        }
        return $this->render('proprietaire/modifier.html.twig', [
            'formulaire' => $form->createView(),
            'proprietaire' => $proprietaire,
        ]);
    }

    #[Route('/proprietaire/chaton/{id}', name: 'proprietaire_chatons')]
    public function chatonProprietaire($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException(
                'Aucun proprietaire trouvé pour cet id : '.$id
            );
        }
        return $this->render('proprietaire/chatons.html.twig', [
            'proprietaire' => $proprietaire,
        ]);
    }
}
