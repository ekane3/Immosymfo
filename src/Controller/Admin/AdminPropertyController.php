<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;
    public function __construct(PropertyRepository $repository,ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route ("/admin", name="admin.property.index")
     * @return Response
     */
    public function index():Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route ("/admin/property/create",name="admin.property.new")
     *
     */
    public function new(Request $request)
    {
        //On instancie un nouveau Property a créer
        $property = new Property();
        //On crée le formulaire de type PropertyType
        $form = $this->createForm(PropertyType::class, $property);
        //On récupère la requête
        $form->handleRequest($request);
        //Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //On persiste le Property
            $this->em->persist($property);
            //On enregistre en base de données
            $this->em->flush();
            //On redirige vers la page d'accueil
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route ("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property,Request $request):Response
    {
        //Creation du formulaire
        $form = $this->createForm(PropertyType::class, $property);
        //Gestion de la requete avec HandleRequest
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return RedirectResponse
     *
     */
    public function delete(Property $property, Request $request):RedirectResponse
    {
        // On vérifie que la requete est de type DELETE
        // et que le token est valide
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            //On supprime le Property
            $this->em->remove($property);
            //On enregistre en base de données
            $this->em->flush();
        }
         return $this->redirectToRoute('admin.property.index');
    }
}
