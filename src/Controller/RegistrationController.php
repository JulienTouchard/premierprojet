<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use \Gumlet\ImageResize;

use function Symfony\Component\Clock\now;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            
            // traitement données bdd
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $createdAt = new \DateTimeImmutable('now');
            $user->setCreatedAt($createdAt);
            $entityManager->persist($user);
            $entityManager->flush();


            //traitement image
            $avatar = $form->get('avatar')->getData();
            if($avatar){
                // $originalExt = $avatar->guessExtension();
                $originalExt = pathinfo($avatar->getClientOriginalName(),PATHINFO_EXTENSION);
                $directory = str_replace('\\','/',$this->getParameter('avatar_directory'));
                $avatar->move($directory,'default.'.$originalExt);
                $copy = new ImageResize($directory.'default.'.$originalExt);
                $copy->resizeToWidth(200);
                $copy->save($directory.$user->getId().".webp", IMAGETYPE_WEBP);
                unlink($directory.'default.'.$originalExt);
            }
            // do anything else you need here, like send an email
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
