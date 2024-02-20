<?php

namespace Innoweb\Breadcrumbs\Extensions;

use Innoweb\Breadcrumbs\Model\Crumb;
use Innoweb\Breadcrumbs\Model\CrumbsList;
use SilverStripe\CMS\Controllers\RootURLController;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;

class SiteTreeExtension extends \SilverStripe\CMS\Model\SiteTreeExtension
{
    // global settings
    private static $crumbs_include_home = true;
    private static $crumbs_show_hidden = false;

    // page settings
    private static $show_crumbs = true;

    public function getCrumbsList()
    {
        $page = $this->owner;
        $pages = [];

        $includeHome = Config::inst()->get(SiteTreeExtension::class, 'crumbs_include_home');
        $showHidden = Config::inst()->get(SiteTreeExtension::class, 'crumbs_show_hidden');

        if ($page->config()->show_crumbs)
        {
            $rootControllerClass = $this->getOwner()->getMultisitesRootControllerClassName();
            if (!empty($rootControllerClass)) {
                $homeLink = $rootControllerClass::get_homepage_link();
                $homePage = SiteTree::get()->filter([
                    'SiteID' => $this->getOwner()->SiteID,
                    'ParentID' => $this->getOwner()->SiteID,
                    'URLSegment' => $homeLink
                ])->first();
            } else {
                $homeLink = RootURLController::get_homepage_link();
                $homePage = SiteTree::get_by_link($homeLink);
            }

            while ($page) {
                if ($page->ID === $homePage->ID) {
                    if ($includeHome) {
                        $pages[] = $page;
                    }
                } else if ($showHidden || $page->ShowInMenus || ($page->ID === $this->owner->ID)) {
                    $pages[] = $page;
                }
                $siteClass = $this->getOwner()->getMultisitesSiteClassName();
                if (!empty($siteClass)) {
                    if ($includeHome && $page->ParentID == $page->SiteID && $page->ID !== $homePage->ID) {
                        $pages[] = $homePage;
                    }
                    $page = $page->ParentID ? $page->Parent() : false;
                    if (is_a($page, $siteClass)) {
                        $page = false;
                    }
                } else {
                    if ($includeHome && !$page->ParentID && $page->ID !== $homePage->ID) {
                        $pages[] = $homePage;
                    }
                    $page = $page->ParentID ? $page->Parent() : false;
                }
            }

        }

        $list = CrumbsList::create();

        if (count($pages)) {
            $pages = array_reverse($pages);
            foreach ($pages as $page) {
                $crumb = Crumb::create($page);
                $list->push($crumb);
            }
        }

        $this->getOwner()->invokeWithExtensions('updateCrumbsList', $list);

        return $list;
    }

    public function getMultisitesRootControllerClassName(): ?string
    {
        $manifest = ModuleLoader::inst()->getManifest();
        if ($manifest->moduleExists('symbiote/silverstripe-multisites')) {
            return \Symbiote\Multisites\Control\MultisitesRootController::class;
        }
        if ($manifest->moduleExists('fromholdio/silverstripe-configured-multisites')) {
            return \Fromholdio\ConfiguredMultisites\Control\MultisitesRootController::class;
        }
        return null;
    }

    public function getMultisitesSiteClassName(): ?string
    {
        $manifest = ModuleLoader::inst()->getManifest();
        if ($manifest->moduleExists('symbiote/silverstripe-multisites')) {
            return \Symbiote\Multisites\Model\Site::class;
        }
        if ($manifest->moduleExists('fromholdio/silverstripe-configured-multisites')) {
            return \Fromholdio\ConfiguredMultisites\Model\Site::class;
        }
        return null;
    }

    public function CrumbsCacheKey()
    {
        $includeHome = Config::inst()->get(SiteTreeExtension::class, 'crumbs_include_home');
        $showHidden = Config::inst()->get(SiteTreeExtension::class, 'crumbs_show_hidden');
        $pageIDs = $this->owner->getAncestors(true)->map()->keys();
        $maxLastEdited = SiteTree::get()->filter('ID', $pageIDs)->max('LastEdited');

        $key = implode('-', $pageIDs);
        $key .= $maxLastEdited;
        $key .= ($includeHome) ? 'hometrue' : 'homefalse';
        $key .= ($showHidden) ? 'hiddentrue' : 'hiddenfalse';

        if ($this->owner->hasMethod('updateCrumbsCacheKey')) {
            $key = $this->owner->updateCrumbsCacheKey($key);
        }
        return $key;
    }
}
