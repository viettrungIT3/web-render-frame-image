<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    private $data           = array();
    private $scripts        = array();
    private $styles         = array();
    private $messages       = array();
    private $errors         = array();
    private $full_layout    = FALSE;
    private $layout_no_header = FALSE;
    private $current_user   = NULL;
    private $body_classes   = array();

    private $format  = "";
    private $request_params   = array();

    private $has_permission = TRUE;
    private $page_title = "";

    const SESS_MSG_KEY = "messages";
    const SESS_ERR_KEY = "errors";
    const KEY_CURRENT_LOB = "sess_current_lob";

    public function __construct()
    {
        parent::__construct();

        $this->page_title = META_DEFAULT_PAGE_TITLE;
        $this->posting_data = $this->get_posting_data();
        $this->request_params = $this->get_request_params();
    } // end construction

    // clearing data
    public function clear_data()
    {
        $this->data = [];
        return $this;
    }

    public function show_404()
    {
        redirect("/error/errors_404");
    }
    // do 404
    public function do_404()
    {
        return $this->clear_data()->failed("File not found")->render_json();
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    } ///

    // function set data
    public function set_params($_data)
    {
        if (is_array($_data) && !empty($_data))
            $this->data = array_merge($this->data, $_data);

        return $this;
    } // end of function set data

    // function get
    public function get($key)
    {
        if (isset($this->data[$key])) return $this->data[$key];
        return false;
    } // end of function get

    // get generic text
    public function get_lang_text($_text, $data = NULL)
    {

        $this->load->helper("language");

        $this->lang->load("general");

        $the_text = $this->lang->line($_text);

        FALSE === $the_text && $the_text = $_text;

        preg_match('/\%[ds]/', $the_text) &&
            NULL !== $data &&
            $the_text = vsprintf($the_text, $data);

        return $the_text;
    } //

    // function to look up the language file
    // and see if there is any key lang
    public function _msg($message, $data = null)
    {

        $this->lang->load("messages");
        $the_message = $this->lang->line($message);

        if (false !== $the_message) :
            $message = vsprintf($the_message, $data === null ? array() : $data);
        endif;


        return $message;
    } //

    // using session to maintain message and error
    public function set_message($message, $data = null)
    {

        $this->lang->load("messages");
        $the_message = $this->lang->line($message);

        if (false !== $the_message) :
            $message = vsprintf($the_message, $data === null ? array() : $data);
        endif;

        $this->messages[] = $message;
        // $this->save_messages_to_sessions();
        return $this;
    } // end of function set message

    // set error
    public function set_error($message, $data = null)
    {
        $this->lang->load("error_messages");
        $the_message = $this->lang->line($message);

        if (false !== $the_message) :
            $message = vsprintf($the_message, $data === null ? array() : $data);
        endif;

        $this->errors[] = $message;
        //$this->save_errors_to_sessions();
        return $this;
    } // end of function set error


    // get the error message
    public function get_error($message, $data = null)
    {
        $this->lang->load("error_messages");
        $the_message = $this->lang->line($message);

        if (false !== $the_message) :
            $message = vsprintf($the_message, $data === null ? array() : $data);
        endif;

        return $message;
    } // end of function set error


    public function get_errors()
    {
        return $this->errors;
    }
    public function get_messages()
    {
        return $this->messages;
    }


    public function failed($message = NULL)
    {
        $this->status = FALSE;
        NULL !== $message && $this->set_error($message);
        return $this;
    }
    public function success($message = NULL)
    {
        $this->status = TRUE;
        NULL !== $message && $this->set_message($message);
        return $this;
    }


    public function render_json($data = NULL)
    {

        NULL === $data  && $data = $this->data;

        $data['status'] = $this->status;

        $errors = $this->get_errors();
        $messages = $this->get_messages();

        !empty($errors) && $data['errors'] = $errors;
        !empty($messages) && $data['messages'] = $messages;

        return $this
            ->output
            ->set_content_type("application/json")
            ->set_output(json_encode($data, "cli" === php_sapi_name() ? JSON_PRETTY_PRINT : 0));
    } // render json

    public function get_request_params()
    {
        $this->load->helpers("url");
        $the_data = $this->input->get();
        return $the_data;
    }


    public function get_posting_data()
    {

        // if cli
        if ("cli" === php_sapi_name()) :
            return json_decode($_SERVER['__posting_data'] ?? "", TRUE);
        endif;


        $this->load->helpers("url");

        $posting_data = $this->input->post();

        if (NULL === $posting_data || empty($posting_data)) :
            $raw_data = file_get_contents('php://input');
            if (NULL !== $raw_data && !empty($raw_data)) :
                $posting_data = json_decode($raw_data, TRUE);
            endif;
        endif;

        return $posting_data;
    }


    public function cli_color($in_char_color = "default", $in_char_text)
    {

        $the_string = $in_char_text;
        $colors =
            [
                "default" => 39,
                "black" => 30,
                "red" => 31,
                "green" => 32,
                "yellow" => 33,
                "blue" => 34,
                "magenta" => 35,
                "cyan" => 36,
                "light gray" => 37,
                "dark gray" => 90,
                "light red" => 91,
                "light green" => 92,
                "light yellow" => 93,
                "light blue" => 94,
                "light magenta" => 95,
                "light cyan" => 96,
                "white" => 97,
            ];

        $color_code = 39;
        isset($colors[$in_char_color]) && $color_code = $colors[$in_char_color];

        return "\e[{$color_code}m{$in_char_text}\e[39m";
    }

    /*
     * @description: to stop any other operation after this
     * @return this
     * */
    public function no_permission()
    {
        $data = $this->data;
        $data['status'] = $this->status;

        $errors = $this->get_errors();
        $messages = $this->get_messages();

        !empty($errors) && $data['errors'] = $errors;
        !empty($messages) && $data['messages'] = $messages;

        $this
            ->output
            ->set_content_type("application/json")
            ->set_output(json_encode($data, "cli" === php_sapi_name() ? JSON_PRETTY_PRINT : 0))
            ->_display();
        exit;
    }


    public function get_formatted_errors()
    {

        $errors = $this->get_errors();

        $err_template = array();

        if (!empty($errors)) :

            foreach ($errors as $single_error) :
                $err_template[] = "<p class='message red'>{$single_error}</p>";
            endforeach;

            return implode("", $err_template);

        endif;

        return "";
    } //

    public function get_formatted_messages()
    {
        $the_messages = $this->get_messages();
        $msg_template = array();
        if (!empty($the_messages)) :
            foreach ($the_messages as $single_message) :
                $msg_template[] = "<p class='message green'> {$single_message}</p>";
            endforeach;

            return implode("", $msg_template);

        endif;
        return "";
    }

    public function get_params()
    {
        return $this->data;
    }

    public function render()
    {

        $messages = [
            "errors"        => $this->get_formatted_errors(),
            "messages"      => $this->get_formatted_messages()
        ];
        $dropdown_menu = null;
        $assets = [

            "params"        => array_merge(
                $this->get_params(),
                [
                    "user" => $the_user ?? NULL,
                    "text_logout" => $this->get_lang_text("action_logout"),
                    "text_login" => $this->get_lang_text("action_login"),
                ],
                $messages,
                [
                    "current_url" => $_SERVER['REQUEST_URI'],
                    "logout_url" => base_url("/login/logout"),
                    "date" => date(SHORT_DATE_FORMAT),
                    "time" => date(TIME_FORMAT),
                ]
            ),
            "body_classes"  => $this->get_body_classes(),
            "styles"        => $this->styles(),
            "scripts"       => $this->scripts(),
            "page_title"            => $this->get_page_title(),
            "full_layout"           => $this->is_full_layout(),
            "layout_no_header"      => $this->layout_no_header

        ];


        $this->lang->load('upload');
        $assets["scripts"] = $assets["scripts"] . "<script> 
                window.__constants = {
                    app_version:        '" . APP_VERSION . "',
                    assets_version:     '" . ASSETS_VERSION . "'
                };
                </script>";

        if (isset($assets['params']['main_template'][0])) :

            $this->load->view(
                "common/template" . $this->get_format(),
                array_merge(
                    $messages,
                    ["data" => $assets]
                )
            );
        endif;
    } // end of function to render

    public function get_format()
    {
        return $this->format;
    }


    // check if running full layout
    public function is_full_layout()
    {
        return $this->full_layout;
    }


    // get page title
    public function get_page_title()
    {
        return $this->page_title;
    }

    public function get_default_assets($type = "css")
    {

        $default_assets = $this->theme->get_assets("default");
        return $default_assets[$type] ?? [];
    } //

    // scripts
    public function get_scripts()
    {
        return $this->scripts;
    }

    // the scripts
    // ====================================

    // styles
    public function get_styles()
    {
        return $this->styles;
    }

    public function scripts()
    {

        $the_scripts = array_merge($this->get_default_assets("js"), $this->get_scripts());
        if (empty($the_scripts)) : return "";
        endif;

        // "development" === ENVIRONMENT && $the_scripts[] = "//localhost:35729/livereload.js";

        $script_template = array();

        foreach ($the_scripts as $single_script) :
            $script_template[] = "<script type='text/javascript' src='{$single_script}?v=" . APP_VERSION . ASSETS_VERSION . "' defer></script>";
        endforeach;

        // adding require js

        $js_meta = $this->get_default_assets("js-meta") ?? [];

        foreach ($js_meta as $single_js) {
            $the_attributes = array();
            if (!empty($single_js['meta'])) :
                foreach ($single_js['meta'] as $meta_key => $single_meta) :
                    $the_attributes[] = " {$meta_key}='{$single_meta}' ";
                endforeach;
            endif;
            $script_template[] = "<script type='text/javascript' " .
                implode(" ", $the_attributes) .
                "src='{$single_js['path']}'></script>";
        }

        return implode($script_template);
    } // ==================================




    // the styles
    // ====================================
    public function styles()
    {

        $the_styles = array_merge($this->get_default_assets("css"), $this->get_styles());

        if (empty($the_styles)) : return "";
        endif;

        $style_template = array();

        foreach ($the_styles as $single_style) :

            $style_template[] = "<link type='text/css' rel='stylesheet' href='{$single_style["path"]}?v=" . APP_VERSION . ASSETS_VERSION . "' media='{$single_style["media"]}' />";

        endforeach;

        return implode("", $style_template);
    } //===================================


    // getting body classes
    public function get_body_classes()
    {
        return implode(" ", $this->body_classes);
    }


    // setting full layou
    public function set_full_layout($is_full_layout = FALSE)
    {
        $this->full_layout = $is_full_layout;
        return $this;
    } //


    public function set_body_class($cls = "")
    {
        if ("" === $cls || in_array($cls, $this->body_classes)) return $this;
        $this->body_classes[] = $cls;
        return $this;
    } //

    // function to set page title
    public function set_page_title($title, $data = NULL)
    {
        $this->load->helper("language");
        $this->lang->load("title");
        $the_title = $this->lang->line($title);

        FALSE === $the_title && $the_title = $title;

        if (preg_match('/\%[ds]/', $the_title) && NULL !== $data) :
            $the_title = vsprintf($the_title, $data);
        endif;

        $this->page_title = $the_title;

        return $this;
    } //


    public function set_main_template($template_path)
    {
        $this->data['main_template'] = $template_path;
        return $this;
    }

    public function upload($path = NULL, $field_name, $file_name = NULL)
    {
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $file_name;
        $config['overwrite'] = TRUE;

        $this->load->library('upload', $config, $field_name);

        if($field_name == "front") {
            $this->front->initialize($config);
            if (!$this->front->do_upload($field_name)) {
                $error = array('error' => $this->front->display_errors());
                return $error;
            } else {
                $data = array('upload_data' => $this->front->data());
                return $data;
            }
        }

        if($field_name == "back") {
            $this->back->initialize($config);
            if (!$this->back->do_upload($field_name)) {
                $error = array('error' => $this->back->display_errors());
                return $error;
            } else {
                $data = array('upload_data' => $this->back->data());
                return $data;
            }
        }
    }

    public function delete_img($file_name = NULL, $path = NULL)
    {
        if ($file_name == NULL) {
            return true;
        }

        $file_path = $path . '/' . $file_name;

        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true; // file không tồn tại, coi như đã xóa thành công
        }
    }


    public function genKey()
    {
        $chars = "0123456789mnbvcxzasdfghjklpoiuytrewq";
        $letter = "";
        $rnd = 20;
        if ((int)$rnd > 0) {
            $letter = substr(str_shuffle(str_repeat($chars, 5)), 0, $rnd);
        }
        return $letter;
    }


    public function dowload(
        $in_url = NULL,
        $in_name_file = NULL
    ) {
        if (file_put_contents($in_name_file, file_get_contents($in_url))) {
            return true;
        } else {
            return false;
        }
    }


    function vn_to_str($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = strtolower(str_replace(' ', '-', $str));

        return $str;
    }

    
} // class core
