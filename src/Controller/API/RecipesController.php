<?php 

namespace App\Controller\API;

use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipesController extends AbstractController
{
    #[Route("/api/recipes")]
    public function index(RecipeRepository $repository) {
        $recipes = $repository->findAll();
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index']
        ]);
    }
}