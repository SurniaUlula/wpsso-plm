=== Place and Local SEO Markup | WPSSO Add-on ===
Plugin Name: WPSSO Place and Local SEO Markup
Plugin Slug: wpsso-plm
Text Domain: wpsso-plm
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-plm/assets/
Tags: local seo, local business, knowledge graph, location, place, address, venue, restaurant, business hours, telephone, coordinates, meta tags
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.0
Tested Up To: 5.4
Stable Tag: 4.7.1

Facebook / Open Graph Location, Pinterest Place, Schema Local Business and Local SEO meta tags.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-plm/assets/icon-256x256.png"></p>

**Let Pinterest, Facebook and Google know about your location(s):**

Include Pinterest Rich Pin *Place*, Facebook / Open Graph *Location*, and Google *Local Business / Local SEO* meta tags in your webpages.

**The WPSSO Place and Local SEO Markup (aka WPSSO PLM) add-on can be used in two different ways:**

* To provide location information for an Organization, which in turn may be related to the content (ie. the content publisher, event organizer, etc.).

* To provide location information for the webpage content (ie. the post/page content is about a specific physical place) (Premium add-on).

Note that the [WPSSO Organization Markup](https://wordpress.org/plugins/wpsso-organization/) (aka WPSSO ORG) add-on is required to select a location for an organization / local business.

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

The Standard version is designed to satisfy the requirements of most standard WordPress sites / blogs. If your site includes posts / pages about specific places, then you may want the Premium version to select specific places for your post / page content.

* Adds a Schema Place tab in the Document SSO metabox to select an address or enter custom address information for the content.

<h3>Markup Examples</h3>

* [Markup Example for a Restaurant](http://wpsso.com/docs/plugins/wpsso-schema-json-ld/notes/markup-examples/markup-example-for-a-restaurant/) using the WPSSO PLM add-on to manage the place information (address, geo coordinates, business hours – daily and seasonal, restaurant menu URL, and accepts reservation values).

<h3>WPSSO Core Plugin Required</h3>

WPSSO Place and Local SEO Markup (aka WPSSO PLM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO PLM Add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/install-the-plugin/)
* [Uninstall the WPSSO PLM Add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/uninstall-the-plugin/)

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

**Version 4.8.0-b.1 (2020/03/10)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for a new WPSSO_SCHEMA_MARKUP_DISABLE constant.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.0.
	* WPSSO Core v6.24.0-b.1.

**Version 4.7.1 (2020/03/02)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed resetting the Schema Type to default if linked / unlinked to a place ID.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.0.
	* WPSSO Core v6.23.2.

== Upgrade Notice ==

= 4.8.0-b.1 =

(2020/03/10) Added support for a new WPSSO_SCHEMA_MARKUP_DISABLE constant.

= 4.7.1 =

(2020/03/02) Fixed resetting the Schema Type to default if linked / unlinked to a place ID.

