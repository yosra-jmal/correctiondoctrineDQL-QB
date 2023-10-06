<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/add', name: 'add_book')]
    public function addBook(ManagerRegistry $manager, Request $request): Response
    {
        $em = $manager->getManager();

        $book = new Book();



        $form = $this->createForm(BookType::class, $book);


        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $book->setPublished(true);
            //Incrémentation nombre des livres pour chaque auteur
            $nb =  $book->getAuthor()->getNb_books() + 1;
            $book->getAuthor()->setNb_books($nb);
            //---------------------------------------------------



            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('list_book');
        }


        return $this->renderForm('book/addBook.html.twig', ['form' => $form]);
    }



    #[Route('/listBook', name: 'list_book')]
    public function listBook(BookRepository $bookrepository): Response
    {

        return $this->render('book/listBook.html.twig', [
            'books' => $bookrepository->findAll(),
        ]);
    }


    #[Route('/book/{id}', name: 'book_details')]
    public function show(BookRepository $bookrepository, $id): Response
    {
        return $this->render('book/showDetails.html.twig', [
            'book' => $bookrepository->find($id),
        ]);
    }

    #[Route('/editBook/{id}', name: 'book_edit')]
    public function editBook(Request $request, ManagerRegistry $manager, $id, BookRepository $bookrepository): Response
    {
        $em = $manager->getManager();

        $book  = $bookrepository->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('list_book');
        }

        return $this->renderForm('book/editBook.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/deleteBook/{id}', name: 'book_delete')]
    public function deleteBook(Request $request, $id, ManagerRegistry $manager, BookRepository $bookRepository): Response
    {
        $em = $manager->getManager();
        $book = $bookRepository->find($id);

        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('list_book');
    }
}
