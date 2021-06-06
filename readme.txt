=== WPSSO Place and Local SEO Markup ===
Plugin Name: WPSSO Place and Local SEO Markup
Plugin Slug: wpsso-plm
Text Domain: wpsso-plm
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-plm/assets/
Tags: local seo, local business, open graph, meta tags, schema, restaurant, facebook, pinterest, google
Contributors: jsmoriss
Requires PHP: 7.0
Requires At Least: 4.5
Tested Up To: 5.7.2
Stable Tag: 5.3.0

Manage Schema Places and Local SEO for Facebook / Open Graph, Pinterest, and Google Local Business.

== Description ==

<p><img class="readme-icon" src="https://surniaulula.github.io/wpsso-plm/assets/icon-256x256.png"> <strong>Let Facebook, Pinterest, and Google know about your places and locations:</strong></p>

Add Facebook / Open Graph location, Pinterest Rich Pin place, and Google Local Business / SEO markup.

**Provides place and location information for WPSSO Core and its add-ons:**

The [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/) can use place information for Facebook / Open Graph location, Pinterest Rich Pin place, and Local SEO meta tags.

The [WPSSO Organization add-on](https://wordpress.org/plugins/wpsso-organization/) can assign place information to its organizations (ie. content publisher, event organizer, etc.).

The [WPSSO Schema JSON-LD Markup add-on](https://wordpress.org/plugins/wpsso-schema-json-ld/) can use place information for the Schema Event location, Schema Job Posting location, and the Schema Place type (and its sub-types) for post / page content about specific places and locations.

<h3>WPSSO PLM Add-on Features</h3>

Extends the features of the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/) (required plugin).

Provides place / location information to WPSSO Core, the WPSSO ORG add-on, and the WPSSO JSON add-on.

Manage one or more places:

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
* Place Timezone
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

The Place and Local SEO Markup Standard add-on is designed to satisfy the requirements of most standard WordPress sites. If your site includes posts / pages about specific places, then you may want to get the [WPSSO PLM Premium add-on](https://wpsso.com/extend/plugins/wpsso-plm/) to select specific places for your post / page content.

**[Premium]** Includes a Schema Place tab in the Document SSO metabox to select an existing place or enter custom place information for post / page content about specific places and locations.

<h3>WPSSO Core Plugin Required</h3>

WPSSO Place and Local SEO Markup (aka WPSSO PLM) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/). WPSSO Core and its add-ons make sure your content looks best on social sites and in search results, no matter how webpages are shared, re-shared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Place and Local SEO Markup add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/install-the-plugin/).
* [Uninstall the WPSSO Place and Local SEO Markup add-on](https://wpsso.com/docs/plugins/wpsso-plm/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

== Screenshots ==

01. The WPSSO PLM settings page includes options to manage location addresses, geo location, business hours, service radius, price and currency information, restaurant menu URL, and more.
02. When editing a post/page, the Schema Place tab provides additional options to manage location addresses, geo location, business hours, service radius, price and currency information, and more (Premium version shown).

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

**Version 5.4.0-dev.4 (2021/06/04)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed all '\*_img_id_pre' option keys to '\*_img_id_lib' for WPSSO 8.30.0.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.30.0-dev.4.

**Version 5.3.0 (2021/04/17)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added support for `SucomForm->get_checklist_post_types()` in the add-on settings page.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.26.3.

**Version 5.2.3 (2021/04/05)**

* **New Features**
	* None.
* **Improvements**
	* Removed the mention of Facebook from the plugin name.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.25.2.

**Version 5.2.2 (2021/02/25)**

* **New Features**
	* None.
* **Improvements**
	* Updated the banners and icons of WPSSO Core and its add-ons.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.25.2.

**Version 5.2.1 (2020/12/22)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* Fixed the 'WpssoPlmFiltersUpgrade->filter_upgraded_md_options' filter that incorrectly cleared post, term, and user metadata options on upgrade.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.18.0.

**Version 5.2.0 (2020/12/04)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Included the `$addon` argument for library class constructors.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.16.0.

**Version 5.1.1 (2020/11/23)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored the `WpssoPlmPlace::get_id()` method to include the place ID.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.4.
	* WPSSO Core v8.13.0.

**Version 5.0.0 (2020/10/26)**

* **New Features**
	* None.
* **Improvements**
	* Added a new "Place Timezone" option.
	* Added 'None' as a possible selection for the "Open Days / Hours" value.
	* Removed the checkbox option for the "Open Days / Hours" weekdays.
* **Bugfixes**
	* Fixed saving of the "Place Name" option value for non-default locale.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.4.
	* WPSSO Core v8.9.0.

== Upgrade Notice ==

= 5.4.0-dev.4 =

(2021/06/04) Renamed option keys for WPSSO 8.30.0.

= 5.3.0 =

(2021/04/17) Added support for `SucomForm->get_checklist_post_types()` in the add-on settings page.

= 5.2.3 =

(2021/04/05) Removed the mention of Facebook from the plugin name.

= 5.2.2 =

(2021/02/25) Updated the banners and icons of WPSSO Core and its add-ons.

= 5.2.1 =

(2020/12/22) Fixed the 'WpssoPlmFiltersUpgrade->filter_upgraded_md_options' filter that incorrectly cleared post, term, and user metadata options on upgrade.

= 5.2.0 =

(2020/12/04) Included the `$addon` argument for library class constructors.

= 5.1.1 =

(2020/11/23) Refactored the `WpssoPlmPlace::get_id()` method to include the place ID.

= 5.0.0 =

(2020/10/26) Added a new "Place Timezone" option for the "Open Days / Hours" values.

