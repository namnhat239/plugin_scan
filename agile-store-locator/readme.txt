=== Store Locator WordPress ===
Contributors: agilelogix
Author URI: http://agilelogix.com/
Plugin URI: https://agilestorelocator.com/
Tags:  business locations, direction, google maps, google maps plugin, location finder, map directions, nearest stores, store locator wordpress, routes, store finder, store locator, street view, store locator widget, wp google maps, wp store locator,wordpress store locator
Requires at least: 3.3.2
Tested up to: 6.0
Donate link: https://codecanyon.net/item/agile-store-locator-google-maps-for-wordpress/16973546
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html




== Description ==

The <a href="https://agilestorelocator.com/">Agile Store locator</a> is the most comprehensive WordPress Store Locator plugin that offers you immediate access to all the stores in your local area.  It enables you to find the very best stores and accurate information about each store that suits a customer inquiry.

= Some Highlighted Features =

* Highly Responsive UI Design
* Stores Management for unlimited Markers
* Category Management with Markers
* Logo Selection for each Store
* API Key insertion Option
* Set Default Lat/Lng for Map
* Sort to Map Screen View
* Find Direction in KM and Miles
* Stylish InfoBox
* Show the Driving Direction of the Store from current location
* Search Direction by Zip, City, State, Country
* GeoLocation API Supported
* Draggable Marker to PinPoint Location in Admin
* Show Timing of Stores
* Category Markers can be applied to Stores
* Customize the Default Zoom Level From Admin Panel
* Enable/Disable a Store
* Customizable Template using Plugin Editor
* Multilingual plugins
* Map Type Selection hybrid, roadmap, satellite and terrain
* and so much more


> <strong>Documentation</strong><br>
> Complete documentation of Store Locator with ShortCodes [documentation](https://agilestorelocator.com/documentation/#demos).


= Premium Version =

> [Buy Agile Store Locator](https://codecanyon.net/item/agile-store-locator-for-wordpress/16973546) | <strong><a href="https://agilestorelocator.com/demos/">Pro Version Demo Link</a></strong>

<strong><a href="https://agilestorelocator.com/demos/store-locator-demo-3/">Demo Template 1</a></strong> | <strong><a href="https://agilestorelocator.com/demos/store-locator-demo-2/">Demo Template 2</a></strong> | <strong><a href="https://agilestorelocator.com/demos/store-locator-demo-1/">Demo Template 3</a></strong>

https://www.youtube.com/watch?v=5GjsKNyJQC4

* 5 Beautiful Themes for the frontend.
* Multiple Layouts with Listing and Accordion Option.
* Accordion template with a hierarchy of Countries, States, Cities and Stores.
* Color Palette for Google Maps Plugin UI Color Selection.
* Multiple Beautiful InfoWindow.
* Extra Template for Deals Websites to show their exciting deals on Maps.
* Extra Template for Real Estate websites to show their Properties and categorize them into Sale, Rent, and Featured.
* Easily customize your info window content, which is a unique feature.
* Easily customize your store list by just adding few keywords.
* Admin Dashboard for Store Locator with all the stats of your markers, stores, categories, and search.
* Analytics Bar Chart to Show user searches which location they have searched most and which store is seen most.
* Analytics Bar Chart to Show Searches, top stores, and top locations.
* Time Selection for Each Day for Every Location.
* Duplicate any Store with a Single Click.
* Add Markers with Each Category, Switch between Category Markers and Default Markers.
* 2 Prompt Location Dialog for GeoLocation.
* Prompt Location 2nd Dialog the ask user to enter his Default Location in case site is not using SSL.
* Assign Multiple Categories to a single store.
* All the ASL Settings can be Overridden by ShortCode Attributes.
* Add Minimum and Maximum Zoom Level for your google maps.
* Fetch Location Coordinates (Lat/Lng) as you type in store address.
* Too many markers? Enable Marker Clustering.
* Full-Width Interactive Google Maps Template.
* Logo Management Panel.
* Marker Manage Panel.
* Choose Stores Time Format 12 or 24 Hours.
* Choose Distance unit Miles/KM.
* Draggable Marker to PinPoint Location.
* Manage Markers icons with names ( UPDATE, ADD and Delete).
* Set the zoom level of marker clicked.
* Manage Categories icons with names ( UPDATE, ADD and Delete).
* Import / Export Stores Excel Sheet with all the columns.
* Delete All Stores with Single Click.
* Choose a Google map type Hybrid, Roadmap, Satellite or Terrain.
* Prompt Location shows the dialog box for confirmation to share current location.
* Show Distance to each Store from Current Location.
* Set Default Zoom of your Map.
* Load on bound fetch Only markers of the screen.
* Custom Filter Option.
* Disable Scroll Wheel.
* Show additional Information about Store.
* Enable/Disable Advance Filter.
* Assign Marker to Each Category and Enable Category Markers.
* Draw Shapes/Circle around your best locations.
* Change Placeholder Text for your search field.
* Show Category Icons instead of Marker icons.
* Enable/Disable Distance Slider.
* Set Default Lat/Lng of your Map.
* Change Header Title Text.
* Change font color for default Template.
* Change Category Title Text.
* Enable/Disable Store List Panel.
* Search Stores with Search by search by Store ID, Title, Description, Street, State, City, Phone, Email, URL, Postal Code, Disabled, Marker, Start Time, End Time, Logo and Created Date.
* Customize your google maps with Drawing Overlay (Polygon, Rectangle, Circle) of Multiple Colors.
* Choose Maps look and feel from Snazzy Maps.
* Search by Address with an auto-panning option.
* Add Google Layers to Show Traffic, Transit, and Bike Layers.
* Enable Marker Animation.
* Restrict your google Search to Country.
* Switch between Google Search and Title Search on Store Locator.
* Enable Full Width for your Plugin.
* Enable/Disable Analytics.
* Enable/Disable Sort by Bound.
* Add Text for "No Item Found".
* 35+ Advance Options for Admin.

Real Estate and Deals Maps

== Installation ==

Installation of this plugin is pretty easy.

1. Upload the extracted plugin folder to the `/wp-content/plugins/` directory of your WordPress installation, or upload it directly from your plugins section in the WordPress admin panel.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That's it!

== Frequently Asked Questions ==

= Multiple inclusion of Google Maps =
The most basic error is because of multiple time inclusion of google maps, so if you facing such issue try to remove the multiple inclusion of this file.

Should be like this
wp_enqueue_script('google-map', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');


How to verify it?
press "CTRL + U" from browser and search for the path below, if its added multiple times then you have multiple inclusion of map.

http://maps.googleapis.com/maps/api/js



== Screenshots ==

1. FrontView
2. FrontView with Infobox
3. FrontView with Direction Dailog
4. FrontView with Direction Panel
5. Add Store Panel



== Installation ==

Installation of this plugin is pretty easy.

1. Upload the extracted plugin folder to the `/wp-content/plugins/` directory of your WordPress installation, or upload it directly from your plugins section in the WordPress admin panel.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. That's it!

== Frequently Asked Questions ==

= Multiple inclusion of Google Maps =
The most basic error is because of multiple time inclusion of google maps, so if you facing such issue try to remove the multiple inclusion of this file.

Should be like this
wp_enqueue_script('google-map', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');


How to verify it?
press "CTRL + U" from browser and search for the path below, if its added multiple times then you have multiple inclusion of map.

http://maps.googleapis.com/maps/api/js



== Screenshots ==

1. FrontView
2. FrontView with Infobox
3. FrontView with Direction Dailog
4. FrontView with Direction Panel
5. Add Store Panel