<?php

namespace dna\core\node_conf {

    require_once __DIR__ . "/package_conf.php";

    use dna\core\package_conf as PKConf;


    /**
     * Class node_package
     * @package dna\core\node_conf
     */
    class node_package
    {
        /**
         * @var string
         */
        public $node_package_ref;
        /**
         * @var PKConf
         */
        public $node_package_desc;
    }
}