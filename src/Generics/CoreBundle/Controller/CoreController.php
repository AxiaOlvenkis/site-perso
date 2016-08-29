<?php

namespace Generics\CoreBundle\Controller;

use Generics\CoreBundle\Entity\Contact;
use Generics\CoreBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function core_menuAction()
    {
        return $this->render('GenericsCoreBundle:Core:menu.html.twig');
    }

    public function socialAction()
    {
        return $this->render('GenericsCoreBundle:Core:social.html.twig');
    }

    public function footerAction()
    {
        return $this->render('GenericsCoreBundle:Core:footer.html.twig');
    }

    public function indexAction()
    {
	    $page = $this->get('page.services')->findOne(array('link'=>'accueil'));

        return $this->render('GenericsCoreBundle:Pages:index.html.twig', array(
	      'page' => $page,
	    ));
    }

    public function pagesAction($intitule)
	{
        $page = $this->get('page.services')->findOne(array('link'=>$intitule));

	    if ($page === null) {
              return $this->render('GenericsCoreBundle:Pages:error404.html.twig', array(
                                    'page' => $page,
                                ));
	    }
	    return $this->render('GenericsCoreBundle:Pages:view.html.twig', array(
	      'page' => $page,
	    ));
	}

	public function contactAction(Request $request)
	{
	    $contact = new Contact();
	    $user = $this->getUser();

	    if($user != null)
	    {
		    if(($user->getPrenom() != '')&&($user->getNom()!=''))
	        {
	        	$nom = $user->getPrenom().' '.$user->getNom();
	            $contact->setNom($nom);
	        }
	        $contact->setMail($user->getEmail());
	    }

	    $form = $this->createForm(ContactType::class, $contact);

	    if ($form->handleRequest($request)->isValid()) {

            // je verifie mon captcha
            $mareponse = $_POST['g-recaptcha-response'];
            $json = $this->get('verif.services')->verifCaptcha($mareponse);

            if(!$json['success'])
            {
                $this->addFlash('info', 'Captcha incorrect.');
                return $this->render('GenericsCoreBundle:Contact:contact.html.twig', array(
                    'form' => $form->createView()
                ));
            }
            /**** Fin de la verif ***/
            // Le reste de la méthode reste inchangé
            $this->get('mail.services')->save($contact);

            $this->get('mail.services')->sendMail(
                                            "Message envoyé depuis le formulaire du site",
                                            'benoit.guelin@wanadoo.fr',
                                            'benoit.guelin@wanadoo.fr',
                                            $contact
                                        );
            $this->addFlash('info', 'Message bien envoyé.');
            return $this->redirect($this->generateUrl('core_contact'));
	    }

	    return $this->render('GenericsCoreBundle:Contact:contact.html.twig', array(
	      'form' => $form->createView()
	    ));		
	}

	public function delete_contactAction(Contact $contact, Request $request)
	{
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('mail.services')->save($contact);
            $request->getSession()->getFlashBag()->add('info', "le message a bien été supprimée.");

            return $this->redirect($this->generateUrl('core_contact_view'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GenericsCoreBundle:Contact:contact_delete.html.twig', array(
                    'contact' => $contact,
                    'form'   => $form->createView()
                ));
	}

	public function contact_viewAction()
	{
        $listContact = $this->get('mail.services')->findAll();

        return $this->render('GenericsCoreBundle:Contact:view_all.html.twig', array(
                    'listContact' => $listContact
                ));
	}

	public function contact_messageAction(Contact $message)
	{
	    return $this->render('GenericsCoreBundle:Contact:view_message.html.twig', array(
	      'message' => $message
	    ));
	}
}
