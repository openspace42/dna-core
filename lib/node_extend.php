<?php

namespace dna\core\package_conf {


    /**
     * Class node_extend represents a node extension in conf.json
     * @package dna\core\package_conf
     */
    class node_extend
    {
        /**
         * @var string a reference es \home\user\.dan\local_node or http://dan.website.com
         */
        public $node_ref = "";
        /**
         * @var string a type of represents default [http]: http, file_system
         */
        public $node_type = "http";
    }
}