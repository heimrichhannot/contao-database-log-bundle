<?php

namespace HeimrichHannot\DatabaseLogBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use HeimrichHannot\DatabaseLogBundle\HeimrichHannotDatabaseLogBundle;

class Plugin implements BundlePluginInterface
{

    /**
     * @inheritDoc
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(HeimrichHannotDatabaseLogBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}