<?php
namespace Generics\AdminBundle\EventListener;


use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Avanzu\AdminThemeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

class SidebarItemListListener {
    public function onSetupMenu(SidebarMenuEvent $event) {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }

    protected function getMenu(Request $request) {
        // Build your menu here by constructing a MenuItemModel array
        $menuItems = array(
            $accueil = new MenuItemModel('accueil', 'Accueil', 'generics_admin_homepage', array(/* options */), 'iconclasses fa fa-home'),
            $site = new MenuItemModel('site', 'Gestion du Site', '', array(/* options */), 'iconclasses fa fa-database'),
            $biblio = new MenuItemModel('biblio', 'Gestion de la Bibliothéque', '', array(/* options */), 'iconclasses fa fa-database'),
        );

        $site->addChild(new MenuItemModel('update_pages', 'Pages Fixes', 'admin_page', array(), 'fa fa-pencil'));
        $site->addChild(new MenuItemModel('update_real', 'Réalisations', 'admin_real', array(), 'fa fa-pencil'));
        $site->addChild(new MenuItemModel('update_contact', 'Messages Reçus', 'core_contact_view', array(), 'fa fa-pencil'));
        $site->addChild(new MenuItemModel('update_users', 'Utilisateurs', 'user_list', array(), 'fa fa-pencil'));

        $biblio->addChild(new MenuItemModel('synchro_api', 'Synchroniser l\'API', 'recup_api', array(), 'fa fa-pencil'));
        $biblio->addChild(new MenuItemModel('add_to_biblio', 'Ajouter à la Bibliothéque', 'liste_api', array(), 'fa fa-pencil'));
        $biblio->addChild(new MenuItemModel('reverse_refuser', 'Voir les élements refusés', 'liste_refuse_api', array(), 'fa fa-pencil'));
        $biblio->addChild(new MenuItemModel('synchro_update_api', 'Synchronisation Update API', 'recup_update', array(), 'fa fa-pencil'));
        $biblio->addChild(new MenuItemModel('update_biblio', 'Editions Bibliothéque', 'biblio_edition', array(), 'fa fa-pencil'));

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    /**
     * @param $route
     * @param array $items
     * @return mixed
     */
    protected function activateByRoute($route, $items) {
        foreach($items as $item) {
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }

}