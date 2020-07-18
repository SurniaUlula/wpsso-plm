=== Place and Local SEO Markup | WPSSO Add-on ===
Plugin Name: WPSSO Place and Local SEO Markup
Plugin Slug: wpsso-plm
Text Domain: wpsso-plm
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-plm/assets/
Tags: local seo, local business, restaurant, meta tags, schema.org, location, place, address, venue, business hours, knowledge graph
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.2
Tested Up To: 5.4.2
Stable Tag: 4.15.1

Manage Places and Local SEO for Facebook / Open Graph, Pinterest, Google Local Business and Schema Place.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-plm/assets/icon-256x256.png"></p>

**Let Facebook, Pinterest and Google know about your places and locations:**

Add Facebook / Open Graph location, Pinterest Rich Pin place and Google Local Business / Local SEO markup.

**Provides place and location information for WPSSO Core and its add-ons:**

*The WPSSO Core plugin* can use place information for Facebook / Open Graph location, Pinterest Rich Pin place and Local SEO meta tags.

*The WPSSO Organization add-on* can assign places to organizations (ie. content publisher, event organizer, etc.).

*The WPSSO Schema JSON-LD Markup add-on* can use place information for the Schema Place type and its sub-types.

<h3>WPSSO PLM Standard Features</h3>

* Extends the features of the WPSSO Core plugin.

* Manage one or more places:

	* Place Schema Type
	* Place Name
	* Place Alternate Name
	* Place Description
	* Street Address
	* P.O. Box Number
	* City
	* State / Province
	* Zip / Postal Code
	* Country
	* Telephone
	* Place Latitude
	* Place Longitude
	* Place Altitude
	* Google Place ID
	* Place Image ID
	* or Place Image URL
	* Open Days / Hours
	* Closes Mid-Day
	* Seasonal Dates
	* Local Business:
		* Service Radius
		* Currencies Accepted
		* Payment Accepted
		* Price Range
	* Food Establishment:
		* Accepts Reservations
		* Serves Cuisine
		* Food Menu URL
		* Order Action URL(s)

<h3>WPSSO PLM Premium Features</h3>

The Standard version is designed to satisfy the requirements of most standard WordPress sites / blogs. If your site includes posts / pages about specific places, then you may want the Premium version to select specific places for your post / page content. You may also want to activate the WPSSO Schema JSON-LD Markup add-on to express place information as Schema Place markup in JSON-LD format.

* Adds a Schema Place tab in the Document SSO metabox to select an existing place or enter custom place information for the content.

<h3>WPSSO Core Plugin Required</h3>

WPSSO Place and Local SEO Markup (aka WPSSO PLM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Place and Local SEO Markup add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/install-the-plugin/).
* [Uninstall the WPSSO Place and Local SEO Markup add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. The WPSSO PLM settings page includes options to manage location addresses, geo location, business hours, service radius, price and currency information, restaurant menu URL, and more.
02. When editing a post/page, the Schema Place tab provides additional options to manage location addresses, geo location, business hours, service radius, price and currency information, and more (Premium add-on).

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-plm/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-plm/)

<h3>Changelog / Release Notes</h3>

**Version 4.15.1 (2020/06/20)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Replaced the 'wpsso_save_options' filter with 'wpsso_save_setting_options' (new in WPSSO Core v7.10.1).
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v7.10.1.

== Upgrade Notice ==

= 4.15.1 =

(2020/06/20) Replaced the 'wpsso_save_options' filter with 'wpsso_save_setting_options'.

