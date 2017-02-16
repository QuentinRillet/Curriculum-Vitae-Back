<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Document;
use BackendBundle\Form\DocumentType;
use CvBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
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

            return $this->redirect($this->generateUrl('backend_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CvBundle:Message');
        $messages = $repo->findBy(array(),array('createdAt' => 'DESC'));

        $delete_forms = array_map(
            function ($element) {
                return $this->createDeleteForm($element)->createView();
            }
            , $messages
        );

        return $this->render('BackendBundle:Default:index.html.twig', array(
            'messages' => $messages,
            'delete_forms' => $delete_forms,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Message $message)
    {
        $form = $this->createDeleteForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('backend_homepage');
    }

    /**
     * Creates a form to delete a Message entity.
     * @param Message $message
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('message_delete', array('id' => $message->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
