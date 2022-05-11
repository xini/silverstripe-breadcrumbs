<?php

namespace Innoweb\Breadcrumbs\Extensions;

use SilverStripe\Core\Extension;

class ContentControllerExtension extends Extension
{
    public function CrumbsList()
    {
        $list = $this->getOwner()->data()->getCrumbsList();
        if ($this->getOwner()->hasMethod('updateCrumbsList')) {
            $list = $this->getOwner()->updateCrumbsList($list);
        }
        return $list;
    }
}
