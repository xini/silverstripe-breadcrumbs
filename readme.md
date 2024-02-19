# SilverStripe Breadcrumbs

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-breadcrumbs)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-breadcrumbs.svg?style=flat-square)](license.md)

## Overview

Adds configurable and extendable breadcrumbs to the site. Compatible with [Symbiote's Multisites](https://github.com/symbiote/silverstripe-multisites) and [Fromholdio's Configured Multisites fork](https://github.com/fromholdio/silverstripe-configured-multisites).

## Requirements

SilverStripe CMS 5, see [composer.json](composer.json)

Note: this version is compatible with Silverstripe 5. 
For Silverstripe 4, please see the [1 release line](https://github.com/xini/silverstripe-breadcrumbs/tree/1).


## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-breadcrumbs dev-master
```
Then run dev/build.

## Configuration

You can configure whether the home page and pages hidden from the sitetree should be inculded in the breadcrumbs:

```
Innoweb\Breadcrumbs\Extensions\SiteTreeExtension:
  crumbs_include_home: false # default: true
  crumbs_show_hidden: true # default: false
```

For a page type you can also disable the crumbs, e.g.:

```
Your\Project\LandingPage:
  show_crumbs: false # default: true
```

## Usage

By default, the module uses the site tree to generate breadcrumbs.  

In your templates, loop over `$CrumbList` to display the breadcrumbs. You can copy the following code into your template:

```
<% if $CrumbsList %>
	<nav aria-label="Breadcrumb" class="breadcrumbs">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
			<% loop $CrumbsList %>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<% if not $IsFirst %>&gt;<% end_if %>
					<a itemtype="http://schema.org/Thing" itemprop="item" href="$Link"<% if $IsLast %> aria-current="page"<% end_if %>>
						<span itemprop="name">$Title</span>
					</a>
					<meta itemprop="position" content="$Pos" />
				</li>
			<% end_loop %>
		</ol>
	</nav>
<% end_if %>
```

A page can use the method `updateCrumbsList()` to add or remove items from the list:

```
public function updateCrumbsList($list) {
	$tag = $this->getActiveTag();
	if ($tag) {
		$crumb = Crumb::create($tag->Title, $tag->Link());
		$list->push($crumb);
	}
	return $list;
}
```

## License

BSD 3-Clause License, see [License](license.md)
