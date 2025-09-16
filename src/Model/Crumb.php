<?php

namespace Innoweb\Breadcrumbs\Model;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Model\ModelData;

class Crumb extends ModelData
{

    protected $page;
    protected $title;
    protected $link;

    public static function factory($source, $link = null)
    {
        $crumb = static::create();
        if (is_a($source, SiteTree::class)) {
            $crumb->setPage($source);
        } else if (is_string($source)) {
            $crumb->setTitle($source);
            if ($link && is_string($link)) {
                $crumb->setLink($link);
            }
        } else {
            user_error('Invalid Crumb source value');
        }
        return $crumb;
    }

    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        $page = $this->getPage();
        if ($page) {
            if ($page->hasMethod('getCrumbTitle')) {
                return $page->getCrumbTitle();
            }
            return $page->MenuTitle ?: $page->Title;
        }
        return $this->title;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function getLink()
    {
        $page = $this->getPage();
        if ($page) {
            if ($page->hasMethod('getCrumbLink')) {
                return $page->getCrumbLink();
            }
            return $page->Link();
        }
        return $this->link;
    }
}
