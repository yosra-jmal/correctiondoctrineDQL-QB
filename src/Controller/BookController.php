<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchBookType;
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
    //DQL: Question 1
    #[Route('/NbrCategory')]
    function NbrCategory(BookRepository $repo){
        $nbr=$repo->NbBookCategory();
        return $this->render('book/showNbrCategory.html.twig', [
            'nbr' => $nbr,
        ]);}
    //DQL: Question 2
    #[Route('/showBookTitle')]
    function showTitleBook(BookRepository $repo){
        $titles=$repo->findBookByPublicationDate();
        return $this->render('book/showTitle.html.twig', [
            'book' => $titles,
        ]);
    }
    //Query Builder: Question 2
    //http://localhost:8000/book/list/search
    #[Route('/book/list/search', name: 'app_book_search', methods: ['GET','POST'])]
    public function searchBook(Request $request,BookRepository $bookRepository): Response
    {   $book=new Book();
        $form=$this->createForm(SearchBookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            return $this->render('book/listSearch.html.twig', [
                'books' => $bookRepository->showAllBooksByAuthor($book->getTitle()),
                'f'=>$form->createView()
            ]);
        }
        return $this->render('book/listSearch.html.twig', [
            'books' => $bookRepository->findAll(),
            'f'=>$form->createView()
        ]);
    }

    //Query Builder: Question 3
    //http://localhost:8000/book/list/author
    #[Route('/book/list/author', name: 'app_book_list_author', methods: ['GET'])]
    public function showOrdredBooksByAuthor(Request $request,BookRepository $bookRepository): Response
    {   if($request->get('title')){
        return $this->render('book/listBookAuthor.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor($request->get('title')),
        ]);
        }
        return $this->render('book/listBookAuthor.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor2(),
        ]);
    }
    //Query Builder: Question 4
    //http://localhost:8000/book/list/author/search/2023-01-01/35
    #[Route('/book/list/author/search/{date}/{nbBooks}', name: 'app_book_list_autho_date', methods: ['GET'])]
    public function showBooksByDateAndNbBooks($date,$nbBooks,Request $request,BookRepository $bookRepository): Response
    {   if($request->get('title')){
        return $this->render('book/listBookDateNbBooks.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor($request->get('title')),
        ]);
        }
        return $this->render('book/listBookDateNbBooks.html.twig', [
            'books' => $bookRepository->showAllBooksAndAuthorByDateAndNbBooks($nbBooks,$date),
        ]);
    }

    //Query Builder: Question 5
    //http://localhost:8000/book/list/author/update/William%20Shakespeare/Romance
    #[Route('/book/list/author/update/{username}/{category}', name: 'app_book_list_author_update', methods: ['GET'])]
    public function updateBooksCategoryByAuthor($username,$category,Request $request,BookRepository $bookRepository): Response
    {   
        if($request->get('title')){
        return $this->render('book/listBookAuthor.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor($request->get('title')),
        ]);
        }
        return $this->render('book/listBookAuthor.html.twig', [
            'books' => $bookRepository->updateBooksCategoryByAuthor($username,$category),
        ]);
    }
}
