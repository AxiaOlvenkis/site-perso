<?php

namespace Generics\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GenericsUserBundle extends Bundle
{
	public function getParent()
	{
	  return 'FOSUserBundle';
	}
}
