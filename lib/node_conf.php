<?php


namespace dna\core {

    include_once __DIR__ . "/node_package.php";

    use dna\core\node_conf\node_package as NP;

    class node_conf
    {

        public $node_name;
        public $node_ref;
        public $node_type;
        public $node_extend = array();
        public $node_packages = array();

        public function __construct($jd)
        {
            if (array_key_exists("node_name", $jd)) {
                $this->node_name = $jd['node_name'];
            }
            if (array_key_exists("node_ref", $jd)) {
                $this->node_ref = $jd['node_ref'];
            }
            if (array_key_exists("node_type", $jd)) {
                $this->node_type = $jd['node_type'];
            }

            //node_extend
            $this->node_extend = array();
            $node_extends = array();
            if (array_key_exists("node_extend", $jd)) {
                $node_extends = $jd['node_extend'];
            }

            foreach ($node_extends as $key => $value) {
                $node_extend = new package_conf\node_extend();
                if (array_key_exists("node_ref", $value)) {
                    $node_extend->node_ref = $value['node_ref'];
                }

                if (array_key_exists("node_type", $value)) {
                    $node_extend->node_type = $value['node_type'];
                }

                array_push($this->node_extend, $node_extend);
            }

            //node_packagers

            $this->node_packages = array();
            $node_packages = array();
            if (array_key_exists("node_packages", $jd)) {
                $node_packages = $jd['node_packages'];
            }

            foreach ($node_packages as $i => $v) {
                $node_package = new NP();
                if (array_key_exists("node_package_desc", $v)) {
                    $node_package->node_package_desc = new package_conf($v['node_package_desc']);
                }
                if (array_key_exists("node_package_ref", $v)) {
                    $node_package->node_package_ref = $v['node_package_ref'];
                }
                array_push($this->node_packages, $node_package);
            }
        }

    }
}