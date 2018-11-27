<?php

namespace dna\core\package_conf {
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/node_extend.php";
    require_once __DIR__ . "/dna_messages.php";

    use dna\core\fileSystem as FS;
    use dna\core as core;

    class ToolAddDependencies
    {
        private $run_path = "";
        /**
         * @var core\package_conf reference to package
         */
        private $pkconf =null;

        /**
         * ToolAddDependencies constructor.
         * @param bool $g
         * @param string $dir
         * @param core\package_conf $pkconf
         */
        public function __construct($dir, $pkconf, $g = false)
        {
            $this->run_path = $dir;
            $userDir = FS::getUserHomeDir();
            $this->pkconf=$pkconf;
            if ($this->pkconf == null) new core\error("sorry but something went wrong");

            if (count($this->pkconf->package_node_extend) == 0) new core\warning("Attention does not have any connected node, use the a option to add one");

            new core\success("Welcome to dna search dependencies tool");
            new core\info("options: s) for search,  a) add node of filter,  import <search index>) to import a dependencies, h) display help, d) display,  e) to exit");
            $in = "";
            while ($in != "e" && $in != "exit") {
                $in = FS::IO_get("option >", false);

                switch ($in) {
                    case 'a':
                    case 'add':
                        new core\info("\toptions: p) for package, n) for node");
                            $insub = FS::IO_get("\toption >", false);
                            switch ($insub){
                                case 'n':case 'node':
                                    $this->AddNode();
                                    break;
                            }
                        break;
                    default:
                        break;
                }
            }
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $this->pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $this->pkconf->simplify());
        }

        private function AddNode(){
            new core\success("\tAdd node tool:");

            $node_ref= FS::IO_get("\treference es \home\user\.dan\local_node or http://dan.website.com (empty to cancel). node_ref: ");
            if ($node_ref=="") return false;
            if (filter_var($node_ref, FILTER_VALIDATE_URL)) {
                $in =FS::IO_get("\t\tYour reference if a website? [y,yes,(empty) for yes | (other) for no]:");
                if ($in =="" or $in == "y" or $in ="yes" ){
                    new core\message("\ttype of represents (empty to cancel): http, file_system: http");
                    $node_type="http";
                }else{
                    goto file;
                }
            }else{
                file:
                $in =FS::IO_get("\t\tYour reference if a folder of this machine? [y,yes,(empty) for yes | (other) for no]:");
                if ($in =="" or $in == "y" or $in ="yes" ) {
                    if(file_exists($node_ref."/dna-node.json") == false){
                        new core\error("\tI can not find a node in this folder",false);
                        return false;
                    }else{
                        new core\message("\ttype of represents (empty to cancel): http, file_system: file_system");
                        $node_type="file_system";
                    }

                }else{
                    new core\error("\tyour node_ref is not compatible with any linking mode",false);
                    return false;
                }
            }
            $node= new node_extend();
            $node->node_ref=$node_ref;
            $node->node_type=$node_type;

            array_push($this->pkconf->package_node_extend,$node);
            new core\success(" \tnode added correctly");
            return true;
        }
    }
}