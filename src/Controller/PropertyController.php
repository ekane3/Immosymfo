<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/property", name="property")
     */
    public function index(): Response
    {
        $properties = $this->repository->findAllVisible();
        $properties[1]->setSold(true);

        dump($properties);

        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties',
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show($slug,$id):Response
    {
        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig',[
            'property' => $property,
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties'
        ]);
    }
}
