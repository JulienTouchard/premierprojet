<?php

namespace App\Controller;

use App\Entity\Moviesfull;
use App\Form\MoviesfullType;
use App\Repository\MoviesfullRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncode;

#[Route('/moviesfull')]
final class MoviesfullController extends AbstractController
{

    #[Route(name: 'app_moviesfull_index', methods: ['GET'])]
    public function index(MoviesfullRepository $moviesfullRepository): Response
    {
        return $this->render('moviesfull/index.html.twig', [
            'moviesfulls' => $moviesfullRepository->findAll(),
        ]);
    }
    #[Route('/film1985', name: 'app_moviesfull_film1985', methods: ['GET', 'POST'])]
    public function film1985(MoviesfullRepository $moviesfullRepository)
    {
        $films = $moviesfullRepository->findBy(['year' => 1985]);
        dd($films);

        //return $this->redirectToRoute('app_moviesfull_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/filmbyyear', name: 'app_moviesfull_filmbyyear', methods: ['GET'])]
    public function filmbyyear(MoviesfullRepository $moviesfullRepository)
    {
        //http://127.0.0.1:8000/moviesfull/filmbyyear?year=1950
        $clientYear = $_GET['year'];
        $films = $moviesfullRepository->findBy(['year' => $clientYear]);
        $jsonResponse = $this->recup($films);
        return new JsonResponse($jsonResponse);
    }
    #[Route('/filmbytitle', name: 'app_moviesfull_filmbytitle', methods: ['GET'])]
    public function filmbytitle(MoviesfullRepository $moviesfullRepository)
    {
        //http://127.0.0.1:8000/moviesfull/filmbytitle?title=young
        $clientTitle = $_GET['title'];
        
        $films = $moviesfullRepository->findLike($clientTitle,'title');

        $jsonResponse = $this->recup($films);
        return new JsonResponse($jsonResponse);
    }
    #[Route('/new', name: 'app_moviesfull_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $moviesfull = new Moviesfull();
        $form = $this->createForm(MoviesfullType::class, $moviesfull);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($moviesfull);
            $entityManager->flush();

            return $this->redirectToRoute('app_moviesfull_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('moviesfull/new.html.twig', [
            'moviesfull' => $moviesfull,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moviesfull_show', methods: ['GET'])]
    public function show(Moviesfull $moviesfull): Response
    {
        return $this->render('moviesfull/show.html.twig', [
            'moviesfull' => $moviesfull,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_moviesfull_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moviesfull $moviesfull, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MoviesfullType::class, $moviesfull);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_moviesfull_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('moviesfull/edit.html.twig', [
            'moviesfull' => $moviesfull,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moviesfull_delete', methods: ['POST'])]
    public function delete(Request $request, Moviesfull $moviesfull, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $moviesfull->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($moviesfull);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_moviesfull_index', [], Response::HTTP_SEE_OTHER);
    }
    public function recup($films)
    {
        $jsonResponse = [];
        foreach ($films as $key => $value) {
            $jsonResponse[$key]['id'] =  $value->getId('id');
            $jsonResponse[$key]['title'] =  $value->getTitle('title');
            $jsonResponse[$key]['year'] =  $value->getYear('year');
            $jsonResponse[$key]['cast'] =  $value->getCast('cast');
            $jsonResponse[$key]['plot'] =  $value->getPlot('plot');
            $jsonResponse[$key]['slug'] =  $value->getSlug('slug');
            $jsonResponse[$key]['directors'] =  $value->getDirectors('directors');
            $jsonResponse[$key]['writers'] =  $value->getWriters('writers');
            $jsonResponse[$key]['runtime'] =  $value->getRuntime('runtime');
            $jsonResponse[$key]['genres'] =  $value->getGenres('genres');
        }
        return $jsonResponse;
    }
}
