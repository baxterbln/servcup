<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    public function __construct() {

        $this->_ci_ob_level  = ob_get_level();

        // Default
        $this->_ci_view_paths = array(
            APPPATH . 'views/' => TRUE
        );

        // Modules
        $module_view_paths = glob(APPPATH . 'modules/*/views/', GLOB_ONLYDIR);

        foreach ($module_view_paths as $module_view_path) {
            $this->_ci_view_paths = array(
                $module_view_path => TRUE,
            );
        }

        $this->_ci_library_paths = array(APPPATH, BASEPATH);

        $this->_ci_model_paths = array(APPPATH);

        $this->_ci_helper_paths = array(APPPATH, BASEPATH);

        log_message('debug', "Loader Class Initialized");

    }
}
