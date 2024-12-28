<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use PHPhinder\SearchEngine;
use PHPhinderBundle\Factory\StorageFactory;
use PHPhinderBundle\Schema\SchemaGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private SearchEngine $searchEngine;

    public function __construct(private StorageFactory $storageFactory, private SchemaGenerator $schemaGenerator)
    {
        $this->searchEngine = new SearchEngine(
            $this->storageFactory->createStorage(
                $this->schemaGenerator->generate(Book::class)
            )
        );
    }

    #[Route('/search', name: 'app_search', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $results = [];

        if ($query) {
            $results = $this->searchEngine->search($query);
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    #[Route('/search/results', name: 'app_search_results', methods: ['GET'])]
    public function searchResults(Request $request): Response
    {
        $query = $request->query->get('q', '');
        $results = $query ? $this->searchEngine->search($query) : [];


        return $this->render('search/_results.html.twig', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    #[Route('/search/results/{id}', name: 'app_result', methods: ['GET'])]
    public function detail(Request $request, int $id, BookRepository $books): Response
    {
        $query = $request->query->get('q', '');
        return $this->render('search/detail.html.twig', [
            'query' => $query,
            'book' => $books->find($id),
        ]);
    }
}