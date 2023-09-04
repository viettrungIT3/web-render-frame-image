<?php
class Theme_model extends MY_Model
{


    private $assets = array(

        "default" => array(

            "css" => array(
                ["path"      => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", "media" => "screen"],
                // ["path"      => "/public/theme/css/layout.css", "media"     => "screen"],
                ["path" => "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.3/css/bootstrap-select.min.css", "media" => "screen"],
            ),

            "js" => [
                // "/public/theme/js/app.js"
            ]

        ),  // end of default assets load

        "default_production" => array(

            "css" => array(),
            "js" => []

        ),  // end of production load
    );


    public function get_assets($key)
    {
        return isset($this->assets[$key]) ? $this->assets[$key] : null;
    } //

    // function to set active nav
    public function set_active_nav($key, $type = NULL)
    {


        return $this;
    } // end of function to set active nav


    // getting the left nav
    public function get_nav($type = "", $args = NULL)
    {

        $the_nav = $this->{"{$type}_nav"} ?? [];

        if (NULL !== $args && !empty($args)) :
            foreach ($the_nav as $k => $single) :
                $the_nav[$k]['path'] = vsprintf($single['path'], $args);
            endforeach;
        endif;

        return $the_nav;
    } 

    public function get_url($slug, $position)
    {
        $the_nav = $this->left_nav;
        $the_nav = $this->{"{$position}_nav"};

        if (isset($the_nav[$slug]) && !empty($the_nav[$slug])) return $the_nav[$slug]['path'];

        return "";
    }

    public function get_site_info()
    {

        return [
            "site_title"    => "Camcard",
            "site_url"      => base_url(),
            "login_url"     => base_url("/login"),
            "logout_url"    => base_url("/login/logout")
        ];

    }
}
