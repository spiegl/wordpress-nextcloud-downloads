
# DMS Downloads

Wordpress Plugin which connects to a public NextCloud (DAV) share and provides a list with files to download from, without showing the user the NextCloud Interface.

## Features

* Download files form a NextCloud public share in your Wordpress website without redirecting
* URL rewrite for beautiful URLs, which do not show that the files are loaded from a DAV server
* Wordpress shortcode to create a list of files

## Getting Started

These instructions will get you a copy of the project up.

### Prerequisites

* Wordpress

Optional

* `mod_rewrite` enabled for beautiful URLs

### Installation

1. Clone the plugin on your server (in the `/wp-content/plugins/` directory).
   1. `git clone git@github.com:spiegl/wordpress-nextcloud-downloads.git dms_downloads`
2. `composer install`
3. Edit the config.php file according to your credentials.
4. Activate the plugin.
5. Use the shortcode `nextcloud_downloads`

## Built With

* [WordPress](https://github.com/WordPress/WordPress)
* [Composer](https://github.com/composer/composer) - Dependency Manager for PHP
* [sabre/dav](http://sabre.io) - sabre/dav is a CalDAV, CardDAV and WebDAV framework for PHP

## License

This project is licensed under the GNU GPL License - see the [LICENSE.md](LICENSE.md) file for details.

## Improvements

This plugin is a frist rough working draft and could be heavily improved!

* Add comments
* Use Wordpress Options API
* Cache the directory listing in order to improve loading time
* Use more than one share link
* Create a namespace for objects, create own files for classes, use properly imports ...
* Abstract class for File and Directory
* Use Wordpress coding standards :-)
* ...