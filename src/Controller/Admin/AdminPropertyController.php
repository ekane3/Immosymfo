<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
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
     * @Route ("/admin", name="admin.property.index")
     * @return Response
     */
    public function index(PropertyRepository $repository)
    {
        $properties = $repository->findAll();

        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route ("/admin/{id}", name="admin.property.edit")
     * @param Property $property
     * @return Response
     */
    public function edit(Property $property,Request $request,ObjectManager $em)
    {
        //Creation du formulaire
        $form = $this->createForm(PropertyType::class, $property);
        //Gestion de la requete avec HandleRequest
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()){
            $this->$em->flush();
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


}
