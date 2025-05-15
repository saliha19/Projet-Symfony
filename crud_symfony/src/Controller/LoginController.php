<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $em->getRepository(Utilisateur::class)->findOneBy([
                'email' => $data['email'],
                'password' => $data['password']
            ]);

            if ($user) {
                $session->set('user_email', $user->getEmail());
                $this->addFlash('success', 'Connexion réussie');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Identifiants incorrects');
        }

        return $this->render('login/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
