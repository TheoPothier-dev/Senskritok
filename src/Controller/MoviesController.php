<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'add_movies', methods: ['POST'])]
    public function createMovie(
        EntityManagerInterface $entityManager,
        Request $request,
        CategoryRepository $categoryRepository
    ): JsonResponse
    {
        // récupérer le contenu de la requête en json
        $data = json_decode($request->getContent(), true);
        
        // instancier une class Movie
        $createdMovie = new Movie();

        //récupérer le title du body de la requête post et l'attitrer à la propriété title
        $createdMovie->setTitle($data["title"]);

        //récupérer la decription du body de la requête post et l'attitrer à la propriété decription
        $createdMovie->setDescription($data["description"]);

        //récupérer l'année du body de la requête post et l'attitrer à la propriété year
        $createdMovie->setYear($data["year"]);

        //correspondance entre l'id récupéré du body de la requête POST et la nom de la catégorie
        foreach ($data["categories"] as $categoryId) {
            $category = $categoryRepository->find($categoryId);
            //attitrer la ou les catégories à la propriétés category
            $createdMovie->addCategory($category);
        }

        // créer l'instance en base de données
        $entityManager->persist($createdMovie);
       
        // appliquer les changements (à exécuter à chaque création / modification / suppression)
        $entityManager->flush();


        return $this->json($createdMovie, Response::HTTP_OK, [], ['groups' => ['movie:read']]);
    }

    #[Route('/movies', name: 'list_movies', methods: ['GET'])]
    public function getMovie(
        Request $request,
        MovieRepository $movieRepository
    ): JsonResponse
    {
        $title = $request->query->get('title');
        $year = $request->query->get('year');

        $movieList = $movieRepository->findMovie($title, $year);

    return $this->json($movieList, Response::HTTP_OK, [], ['groups' => ['movie:read']]);
        
    }

}
