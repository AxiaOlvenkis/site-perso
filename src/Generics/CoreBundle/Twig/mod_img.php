<?php
namespace Generics\CoreBundle\Twig;

class mod_img extends \Twig_Extension
{
  /**
   *
   * @param string $texte
   * @return bool
   */
  public function format_img($texte)
  {
    $url = 'http://'.$_SERVER['HTTP_HOST']."/Projets%20web/site_perso";
    $chaine = $url."/web/images/articles/";
    $mon_texte = str_replace('src="', 'src="'.$chaine, $texte);

    return $mon_texte;
  }

  public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('img_format', array($this, 'format_img')),
        );
    }

  public function getName()
  {
    return 'imgFormat';
  }
}