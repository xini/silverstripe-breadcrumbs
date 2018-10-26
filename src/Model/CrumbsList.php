<?php

namespace Innoweb\Breadcrumbs\Model;

use SilverStripe\ORM\ArrayList;

class CrumbsList extends ArrayList
{
    public function __construct($items = [])
    {
        if (!is_array($items) || func_num_args() > 1) {
            $items = func_get_args();
        }
        parent::__construct($items);
    }

    public function insertBefore($link, $item)
    {
        $i = 0;
        foreach ($this as $child) {
            if ($link == $child->getLink()) {
                array_splice($this->items, $i, 0, [$item]);
                return $item;
            }
            $i++;
        }
        return false;
    }

    public function insertAfter($link, $item)
    {
        $i = 0;
        foreach ($this as $child) {
            if ($link == $child->getLink()) {
                array_splice($this->items, $i+1, 0, [$item]);
                return $item;
            }
            $i++;
        }
        return false;
    }

    public function removeByLink($link)
    {
        if (is_array($link)) {
            foreach ($link as $singleLink) {
                $this->removeByLink($singleLink);
            }
            return $this;
        }

        foreach ($this as $i => $child) {
            $childLink = $child->getLink();
            if ($childLink === $link) {
                $this->remove($child);
                break;
            }
        }

        return $this;
    }

    public function removeByPageID($pageID)
    {
        if (is_array($pageID)) {
            foreach ($pageID as $singlePageID) {
                $this->removeByPageID($singlePageID);
            }
        }

        $pageID = (int) $pageID;

        foreach ($this as $i => $child) {
            $childPage = $child->getPage();
            if ($childPage) {
                $childPageID = (int) $childPage->ID;
                if ($childPageID === $pageID) {
                    $this->remove($child);
                    break;
                }
            }
        }

        return $this;
    }
}