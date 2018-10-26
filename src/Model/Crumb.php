<?php

namespace Innoweb\Breadcrumbs\Model;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\ViewableData;

class Crumb extends ViewableData
{

    protected $page;
    protected $title;
    protected $link;

    public function __construct($source, $link = null)
    {
        if (is_a($source, SiteTree::class)) {
            $this->setPage($source);
        } else if (is_string($source)) {
            $this->setTitle($source);
            if ($link && is_string($link)) {
                $this->setLink($link);
            }
        } else {
            user_error('Invalid Crumb source value');
        }
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