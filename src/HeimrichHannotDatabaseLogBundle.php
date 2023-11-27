<?php

namespace HeimrichHannot\DatabaseLogBundle;

use HeimrichHannot\DatabaseLogBundle\DependencyInjection\HeimrichHannotDatabaseLogExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotDatabaseLogBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension()
    {
        return new HeimrichHannotDatabaseLogExtension();
    }


}