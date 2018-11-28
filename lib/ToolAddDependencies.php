<?php

namespace dna\core\package_conf {
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/node_extend.php";
    require_once __DIR__ . "/dna_messages.php";
    require_once __DIR__ . "/network.php";
    require_once __DIR__ . "/node_conf.php";

    use dna\core\fileSystem as FS;
    use dna\core as core;
    use dna\core\network as NET;
    use dna\core\node_conf as NC;

    class ToolAddDependencies
    {
        private $run_path = "";
        /**
         * @var core\package_conf reference to package
         */
        private $pkconf = null;
        private $filters = null;

        private $Result = array();

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
            $this->pkconf = $pkconf;
            $this->filters = new core\package_conf(array());
            if ($this->pkconf == null) new core\error("sorry but something went wrong");

            if (count($this->pkconf->package_node_extend) == 0) new core\warning("Attention does not have any connected node, use the a option to add one");

            new core\success("Welcome to dna search dependencies tool");
            $in = "";
            while ($in != "e" && $in != "exit") {
                $ref = $this->filters->renderingFilter();
                if ($ref !== "") new core\success("Filters: " . $ref);
                new core\info("options: s) for search,  a) add node of filter,  import <search index>) to import a dependencies, h) display help, d) display,  e) to exit");

                $in = FS::IO_get("option >", false);

                switch ($in) {
                    case 'a':
                    case 'add':
                        new core\info("\toptions: f) for package filter, n) for node");
                        $insub = FS::IO_get("\toption >", false);
                        switch ($insub) {
                            case 'n':
                            case 'node':
                                $this->AddNode();
                                break;
                            case 'f':
                            case 'filter':
                                $this->AddFilter();
                                break;
                            default:
                                break;
                        }
                        break;
                    case 's':
                    case 'search':
                        $this->Search();
                        break;
                    default:
                        break;
                }
            }
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $this->pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $this->pkconf->simplify());
        }

        private function AddNode()
        {
            new core\success("\tAdd node tool:");

            $node_ref = FS::IO_get("\treference es \home\user\.dan\local_node or http://dan.website.com (empty to cancel). node_ref: ");
            if ($node_ref == "") return false;
            if (filter_var($node_ref, FILTER_VALIDATE_URL)) {
                $in = FS::IO_get("\t\tYour reference if a website? [y,yes,(empty) for yes | (other) for no]:");
                if ($in == "" or $in == "y" or $in = "yes") {
                    new core\message("\ttype of represents (empty to cancel): http, file_system: http");
                    $node_type = "http";
                } else {
                    goto file;
                }
            } else {
                file:
                $in = FS::IO_get("\t\tYour reference if a folder of this machine? [y,yes,(empty) for yes | (other) for no]:");
                if ($in == "" or $in == "y" or $in = "yes") {
                    if (file_exists($node_ref . "/dna-node.json") == false) {
                        new core\error("\tI can not find a node in this folder", false);
                        return false;
                    } else {
                        new core\message("\ttype of represents (empty to cancel): http, file_system: file_system");
                        $node_type = "file_system";
                    }

                } else {
                    new core\error("\tyour node_ref is not compatible with any linking mode", false);
                    return false;
                }
            }
            $node = new node_extend();
            $node->node_ref = $node_ref;
            $node->node_type = $node_type;

            array_push($this->pkconf->package_node_extend, $node);
            new core\success(" \tnode added correctly");
            return true;
        }

        private function AddFilter()
        {
            new core\success("\t\tAdd filter tool:");
            new core\info("\t\ta) package_name          b) package_version          c) package_uid                             d) package_author_group\n" .
                "\t\te) package_copyright     f) package_licenseUrl       g) package_require_license_acceptance      h) package_websiteUrl\n" .
                "\t\ti) package_docUrl        l) package_author           m) package_release_note                    n) package_description\n" .
                "\t\to) package_iconUrl       p) package_tag              (other or empty for cancel)");
            $in = FS::IO_get("\t\tfilter >", false);
            switch ($in) {
                case 'a':
                    $data = FS::IO_get("\t\t\tpackage_name (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_name = $data;
                    }
                    break;
                case 'b':
                    $data = FS::IO_get("\t\t\tpackage_version (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_version = $data;
                    }
                    break;
                case 'c':
                    $data = FS::IO_get("\t\t\tpackage_uid (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_uid = $data;
                    }
                    break;
                case 'd':
                    $data = FS::IO_get("\t\t\tpackage_author_group (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_author_group = $data;
                    }
                    break;
                case 'e':
                    $data = FS::IO_get("\t\t\tpackage_copyright (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_copyright = $data;
                    }
                    break;
                case 'f':
                    $data = FS::IO_get("\t\t\tpackage_licenseUrl (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_licenseUrl = $data;
                    }
                    break;
                case 'g':
                    $data = FS::IO_get("\t\t\tpackage_require_license_acceptance (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_require_license_acceptance = $data;
                    }
                    break;
                case 'h':
                    $data = FS::IO_get("\t\t\tpackage_websiteUrl (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_websiteUrl = $data;
                    }
                    break;
                case 'i':
                    $data = FS::IO_get("\t\t\tpackage_docUrl (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_docUrl = $data;
                    }
                    break;
                case 'l':
                    new core\info("\t\t\ta) author_name       b) author_surname       c) author_nic        d) author_link        e) author_other");
                    $insub = FS::IO_get("\t\t\tpackage_author filter (empty for cancel)>", false);
                    switch ($insub) {
                        case 'a':
                            $data = FS::IO_get("\t\t\t\tauthor_name (empty for cancel)>", false);
                            if (isset($data) or $data != "") {
                                $this->filters->package_author->author_name = $data;
                            }
                            break;
                        case 'b':
                            $data = FS::IO_get("\t\t\t\tauthor_surname (empty for cancel)>", false);
                            if (isset($data) or $data != "") {
                                $this->filters->package_author->author_surname = $data;
                            }
                            break;
                        case 'c':
                            $data = FS::IO_get("\t\t\t\tauthor_nic (empty for cancel)>", false);
                            if (isset($data) or $data != "") {
                                $this->filters->package_author->author_nic = $data;
                            }
                            break;
                        case 'd':
                            $data = FS::IO_get("\t\t\t\tauthor_link (empty for cancel)>", false);
                            if (isset($data) or $data != "") {
                                $this->filters->package_author->author_link = $data;
                            }
                            break;
                        case 'e':
                            $data = FS::IO_get("\t\t\t\tauthor_other (empty for cancel)>", false);
                            if (isset($data) or $data != "") {
                                $this->filters->package_author->author_other = $data;
                            }
                            break;
                        default:
                            break;
                    }

                    break;
                case 'm':
                    $data = FS::IO_get("\t\t\tpackage_release_note (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_release_note = $data;
                    }
                    break;
                case 'n':
                    $data = FS::IO_get("\t\t\tpackage_description (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_description = $data;
                    }
                    break;
                case 'o':
                    $data = FS::IO_get("\t\t\tpackage_iconUrl (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->package_iconUrl = $data;
                    }
                    break;
                case 'p':
                    $data = FS::IO_get("\t\t\tpackage_tag (empty for cancel)>", false);
                    if (isset($data) or $data != "") {
                        $this->filters->addTag($data);
                    }
                    break;
                default:
                    break;
            }
        }

        private function Search()
        {
            $this->Result = array();
            $nodes = $this->pkconf->WithDefault()->package_node_extend;
            $this->SearchEngine($nodes);
        }

        private function SearchEngine($listOfnode)
        {
            $nextStep = array();
            /**
             * @var node_extend $v
             */
            foreach ($listOfnode as $i => $v) {
                if ($v->node_type == "http") {
                    if (NET::URLIsValid($v->node_ref . "/dna-node.json")) {
                        $node = new NC(NET::openJson($v->node_ref . "/dna-node.json"));
                        foreach ($node->node_extend as $i => $v) {
                            array_push($nextStep, $v);
                        }
                        $this->SearchEngineLiker($node->node_packages);
                    } else {
                        new core\error($v->node_ref . " skipped because don't have dna-node.json", false);
                    }
                } else if ($v->node_type === "file_system") {

                }
            }
            if (count($nextStep) !== 0) {
                $this->SearchEngine($nextStep);
            }
        }

        private function SearchEngineLiker($node_packages)
        {
            /**
             * @var NC\node_package $package
             */
            foreach ($node_packages as $i => $package) {
                if ($this->filters->match($package->node_package_desc)) {
                    array_push($this->Result, $package);
                    new core\success("Search index : " . (count($this->Result)) . ") \n" . $package->node_package_desc->renderingSearch());
                }
            }
        }
    }
}