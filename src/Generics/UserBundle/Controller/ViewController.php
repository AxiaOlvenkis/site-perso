<?php
namespace Generics\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Util\UserManipulator;
use UserBundle\Form\MyProfilType;
use UserBundle\Entity\User;
use BlogBundle\Entity\Commentaire;

class ViewController extends Controller
{
    public function user_menuAction()
    {
        return $this->render('GenericsUserBundle:Global:user_menu.html.twig');
    }

    public function user_listAction()
    {
    	$userManager = $this->get('fos_user.user_manager');
        $listUsers = $userManager->findUsers();

    	return $this->render('GenericsUserBundle:Security:liste_user.html.twig', array(
	      'listUsers' => $listUsers
	      ));
    }

    public function give_roleAction($user, $role)
    {
		$em = $this->getDoctrine()->getManager();
    	$userManager = $this->get('fos_user.user_manager');
    	$userManipulator = new UserManipulator($userManager);
    	$rep = $userManipulator->addRole($user, $role);

        return $this->redirect($this->generateUrl('user_list'));
    }

    public function remove_roleAction($user, $role)
    {
    	$em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $rep = $userManipulator->removeRole($user, $role);

        return $this->redirect($this->generateUrl('user_list'));
    }

    public function activeAction($user)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $rep = $userManipulator->activate($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    public function desactiveAction($user)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $rep = $userManipulator->deactivate($user);

        return $this->redirect($this->generateUrl('user_list'));
    }

    public function profil_editAction(Request $request, $id)
    {        
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if (null === $user) {
          throw new NotFoundHttpException("L'utilisateur' d'id ".$id." n'existe pas.");
        }

        $form = $this->createForm(MyProfilType::class, $user);

        if ($form->handleRequest($request)->isValid()) {
          // Inutile de persister ici, Doctrine connait déjà notre annonce, vu qu'on lui a demander de nous la recuperer.
            if(($user->getPrenom() == '')||($user->getNom()==''))
            {
                $user->setAnonyme(false);
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien modifiée.');

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('GenericsUserBundle:Profile:edit.html.twig', array(
          'form'   => $form->createView(),
          'user' => $user // Je passe également l'annonce à la vue si jamais elle veut l'afficher
        ));
    }

    public function delete_userAction(User $user, Request $request)
    {       
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
          $em->remove($user);
          $em->flush();

          $request->getSession()->getFlashBag()->add('info', "l'utilisateur' a bien été supprimée.");

          return $this->redirect($this->generateUrl('user_list'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('GenericsUserBundle:Global:delete.html.twig', array(
          'user' => $user,
          'form'   => $form->createView()
        ));
    }
}