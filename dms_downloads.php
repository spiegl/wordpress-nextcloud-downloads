<?php
/*
Plugin Name: DMS Downloads
Plugin URI: http://spiegl.me/
Description: Wordpress Plugin which connects to a public NextCloud (DAV) share and provides a list with files to download from, without showing the user the NextCloud Interface.
Author: Daniel Mur-Spiegl
Version: 1.0
Author URI: http://spiegl.me
 */

require_once 'dav_client.php';

## Define functions and classes

function crop_url(string $url)
{
    return str_replace('/public.php/webdav/', '', $url);
}

class DMS_Directory
{
    public $_name = '';

    public $_dirs = [];
    public $_files = [];

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function get_name(){
        return \Sabre\HTTP\decodePath($this->_name);
    }

    public function create_dir($dir)
    {
        if (!isset($this->_dirs[$dir])) {
            $this->_dirs[$dir] = new DMS_Directory($dir);
        }
        return $this->_dirs[$dir];
    }

    public function add_dir($dir)
    {
        if (!in_array($dir, $this->_dirs)) {
            array_push($this->_dirs, $dir);
        }
        return $dir;
    }

    public function create_file($file, $key = null)
    {
        if (!isset($this->_files[$file])) {
            $this->_files[$file] = new DMS_File($file);
            $this->_files[$file]->_key = $key;
        }
        return $this->_files[$file];
    }

    public function add_file($file)
    {
        if (!in_array($file, $this->_files)) {
            array_push($this->_files, $file);
        }
    }

    public function print_tree()
    {
        $echo = '<ul>';
        foreach ($this->_dirs as $dir) {
            $echo .= '<li>' . $dir->get_name();
            $echo .= $dir->print_tree();
            $echo .= '</li>';
        }
        foreach ($this->_files as $file) {
            $echo .= '<li><a href="' . $file->get_link() . '">' . $file->get_name() . '</a></li>';
        }
        $echo .= '</ul>';
        return $echo;
    }

}

class DMS_File
{

    public $_name;
    public $_key;
    protected $config;

    public function __construct($name)
    {
        $this->_name = $name;
        $this->config = $GLOBALS['config'];
    }

    public function get_name(){
        return \Sabre\HTTP\decodePath($this->_name);
    }

    protected function get_short_key()
    {
        return str_replace($this->config['uri_prefix'], '', $this->_key);
    }

    public function get_link()
    {
        if ($this->config['mod_rewrite_is_enabled']) {
            return '/wp-content/plugins/dms_downloads/' . $this->get_short_key() . '"';
        } else {
            return '/wp-content/plugins/dms_downloads/download.php?file=' . $this->get_short_key() . '"';
        }

    }

}

// Get folder content
$folder_content = $client->propFind('', array(
    '{DAV:}displayname',
    '{DAV:}getcontentlength',
    '{DAV:}getlastmodified',
    '{DAV:}getcontenttype',
), 3);

$root = new DMS_Directory('/');

foreach ($folder_content as $key => $value) {
    $name = crop_url($key);

    $parts = explode('/', $name);

    $current = $root;
    $j = 0;
    foreach ($parts as $part) {
        if (empty($part)) {
            continue;
        }
        if (!(count($parts) - 1 == $j && isset($value['{DAV:}getcontenttype']))) {
            $current = $current->create_dir($part);
        } else {
            $current->create_file($part, $key);
        }
        $j++;
    }
}

function nextcloud_downloads()
{
    global $root;
    return $root->print_tree();
}
add_shortcode('nextcloud_downloads', 'nextcloud_downloads');
