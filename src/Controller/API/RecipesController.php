<?php 

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class RecipesController extends AbstractController
{
    #[Route("/api/recipes", methods: ["GET"])]
    public function index(
        RecipeRepository $repository, 
        #[MapQueryString()]
        PaginationDTO $paginationDTO = null
    ) 
    {
        $recipes = $repository->paginateRecipes($paginationDTO?->page);
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index']
        ]);
    }

    #[Route("/api/recipes{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe) 
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

    #[Route("/api/recipes", methods:["POST"])]
    public function create(
        Request $request, 
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['recipes.create'] 
            ]
        )]
        Recipe $recipe,
        EntityManagerInterface $em
    ) 
    {
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipe);   
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);  
    }
}