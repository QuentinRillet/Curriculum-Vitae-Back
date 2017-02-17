<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Document;
use BackendBundle\Form\DocumentType;
use CvBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentController extends Controller
{
    public function cvAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Symfony\Component\HttpFoundation\File\UploadedFile $file
             */
            $file = $document->getNom();

            //$fileName = md5(uniqid()).'.'.$file->guessExtension();
            $fileName = 'CV-Resume-Quentin-Rillet.'.$file->guessExtension();

            $file->move(
                $this->getParameter('documents_directory'),
                $fileName
            );

            $document->setNom($fileName);

            $this->addFlash(
                'notice',
                'Votre CV est en ligne'
            );

            return $this->redirect($this->generateUrl('backend_cv'));
        }

        return $this->render('BackendBundle:Document:cv.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function recommendationAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Symfony\Component\HttpFoundation\File\UploadedFile $file
             */
            $file = $document->getNom();

            //$fileName = md5(uniqid()).'.'.$file->guessExtension();
            $fileName = 'recommendation-Quentin-Rillet.'.$file->guessExtension();

            $file->move(
                $this->getParameter('documents_directory'),
                $fileName
            );

            $document->setNom($fileName);

            $this->addFlash(
                'notice',
                'Votre lettre est en ligne'
            );

            return $this->redirect($this->generateUrl('backend_recommendation'));
        }

        return $this->render('BackendBundle:Document:recommendation.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
