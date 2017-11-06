<?php

namespace Lpmr\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LpmrUserBundle extends Bundle
{
    public function getParent()
   {
       return 'FOSUserBundle';
   }
}
