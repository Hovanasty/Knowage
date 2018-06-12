<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Formation;
use AppBundle\Form\addFormationType;
use AppBundle\Service\ImgUploader;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Twig\Node\Expression\GetAttrExpression;


class FormationController extends controller
{

    /**
     * @Route("/show", name="show")
     * @Method({"GET", "POST"})
     */
    public function landingFormationAction()
    {
        return $this->render('Formation/landingFormation.html.twig');
    }

    /**
     * @Route("/creation", name="create")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request, ImgUploader $imgUpload)
    {
        $formation = new Formation();

        $form = $this->createForm('AppBundle\Form\addFormationType', $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $picture = $formation->getPicture();
            $formation->setPicture($imgUpload->uploadFormationPicture($picture));
            $formation->setCreatedAt(new \DateTime());
            $formation->setAuthor($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('create2', array(
                'id' => $formation->getId()
            ));
        }

        return $this->render('Formation/create.html.twig', array(
            'form'=>$form->createView()
        ));

    }

    /**
     * @Route("/creation2/{id}", name="create2")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function create2Action(Request $request, Formation $formation, $id)
    {

        if ($formation->getAuthor() == $this->getUser()) {

            $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);

            $form = $this->createForm('AppBundle\Form\FormationType', $formation);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formation_show', array(
                'id' => $id
            ));
        }

        return $this->render('Formation/create2.html.twig', array(
            'form'=>$form->createView()
        ));
    }


    /**
     * @Route("/teacher", name="landingformateur")
     */
    public function landingFormateurAction()
    {
        return $this->render('Front/landingFormateur.html.twig');
    }


    /**
     * Finds and displays a formation entity.
     *
     * @Route("/formation/{id}", name="formation_show")
     * @Method("GET")
     */
    public function showAction(Formation $formation, $id)
    {

        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);

        return $this->render('Formation/FormationAbonne.html.twig', array(
            'formation' => $formation,
        ));
    }

}
