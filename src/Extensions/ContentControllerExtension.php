<?php

namespace Innoweb\Breadcrumbs\Extensions;

use SilverStripe\Core\Extension;

class ContentControllerExtension extends Extension
{
    public function CrumbsList()
    {
        $list = $this->getOwner()->data()->getCrumbsList();
        $this->getOwner()->invokeWithExtensions('updateCrumbsList', $list);
        return $list;
    }
}
