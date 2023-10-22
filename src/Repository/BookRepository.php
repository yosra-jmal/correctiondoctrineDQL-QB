<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//Query Builder: Question 2
    public function showAllBooksByAuthor($title)
        {
            return $this->createQueryBuilder('b')
                ->join('b.author','a')
                ->addSelect('a')
                ->where('b.title LIKE :title')
                ->setParameter('title', '%'.$title.'%')
                ->getQuery()
                ->getResult()
            ;
        }
//Query Builder: Question 3
        public function showAllBooksByAuthor2()
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->addSelect('a')
            ->orderBy('b.title','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//Query Builder: Question 4
    public function showAllBooksAndAuthorByDateAndNbBooks($nbooks,$year)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->addSelect('a')
            ->where('a.nb_books > :nbooks')
            ->andWhere("b.publicationDate < :year")
            ->setParameter('nbooks',$nbooks)
            ->setParameter('year',$year)
            ->getQuery()
            ->getResult()
        ;
    }

//Query Builder: Question 5
    public function updateBooksCategoryByAuthor($authorUsername, $newCategory)
    {
        // Step 1: Fetch the entities that need to be updated
        $books = $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('a.username LIKE :username')
            ->setParameter('username', '%'.$authorUsername.'%')
            ->getQuery()
            ->getResult();
        // Step 2: Apply the updates
        foreach ($books as $book) {
            $book->setCategory($newCategory);
        }

        // Flush the changes
        $this->getEntityManager()->flush();

        return $books
    ;
    }



// DQL
//Question 1
function NbBookCategory(){
    $em=$this->getEntityManager();
    return $em->createQuery('select count(b) from App\Entity\Book b WHERE b.category=:category')
    ->setParameter('category','Science Fiction')->getSingleScalarResult();
}
//Question 2
function findBookByPublicationDate(){
    $em=$this->getEntityManager();
    return $em->createQuery('select b from App\Entity\Book b WHERE 
    b.publicationDate BETWEEN ?1 AND ?2')
    ->setParameter(1,'2014-01-01')
    ->setParameter(2,'2018-01-01')->getResult();
}

}
