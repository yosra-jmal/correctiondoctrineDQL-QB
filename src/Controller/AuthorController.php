<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    //Exercice 1 : Twig et Affichage d’une variable
    #[Route('/showauthor/{name}', name: 'show_author')]
    public function showAuthor($name)
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }


    // Exercice 2 : Manipulation du tableau associatif, filtre et Structure conditionnelle
    #[Route('/list', name: 'list_author')]
    public function list()
    {

        $authors = array(

            array('id' => 1, 'username' => ' Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'username' => ' Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );


        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/{id}', name: 'author_details')]
    public function authorDetails($id)
    {
        $author = null;
        $authors = array(

            array('id' => 1, 'username' => ' Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'username' => ' Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        // Parcourez le tableau pour trouver l'auteur correspondant à l'ID
        foreach ($authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            };
        };
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
            'id' => $id
        ]);
    }
}
