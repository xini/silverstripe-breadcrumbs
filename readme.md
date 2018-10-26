# SilverStripe Breadcrumbs

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-breadcrumbs)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-breadcrumbs.svg?style=flat-square)](license.md)

## Overview

Adds configurable and extendable breadcrumbs to the site.

## Requirements

SilverStripe CMS 4, see [composer.json](composer.json)

## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-breadcrumbs dev-master
```
Then run dev/build.

## Usage

By default, the module uses the site tree to generate breadcrumbs.  

In you templates, loop over `$CrumbList` to display the breadcrumbs. You cancopy the following code into your template:

```
<% if $CrumbsList %>
<nav aria-label="Breadcrumb" class="breadcrumbs">
	<ol itemscope itemtype="http://schema.org/BreadcrumbList">
		<% loop $CrumbsList %>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<% if not $First %>&gt;<% end_if %>
	   			<a itemtype="http://schema.org/Thing" itemprop="item" href="$Link"<% if $Last %> aria-current="page"<% end_if %>>
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
