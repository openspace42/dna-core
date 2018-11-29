<?php

namespace dna\core {

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
    use dna\core\package_conf\node_extend;

    /**
     * Class ToolNodeSearch
     * @package dna\core
     */
    class ToolNodeSearch
    {
        /**
         * @param $listOfNode
         * @param mixed|package_conf $filtes
         * @param array|NC\node_package $Result
         * @param bool $display
         * @return array|NC\node_package
         */
        public static function SearchEngine($listOfNode,$filtes,$Result=array(),$display=true)
        {
            $nextStep = array();
            /**
             * @var node_extend $v
             */
            foreach ($listOfNode as $i => $v) {
                if ($v->node_type == "http") {
                    if (NET::URLIsValid($v->node_ref . "/dna-node.json")) {
                        $node = new NC(NET::openJson($v->node_ref . "/dna-node.json"));
                        foreach ($node->node_extend as  $v2) {
                            array_push($nextStep, $v2);
                        }
                        $Result=ToolNodeSearch::SearchEngineLiker($node->node_packages,$filtes,$Result,$display);
                    } else {
                        new core\error($v->node_ref . " skipped because don't have dna-node.json or you are offline", false);
                    }
                } else if ($v->node_type === "file_system") {
                    if (file_exists($v->node_ref . "/dna-node.json")) {
                        $node = new NC(FS::openJson($v->node_ref . "/dna-node.json"));
                        foreach ($node->node_extend as  $v2) {
                            array_push($nextStep, $v2);
                        }
                        $Result=ToolNodeSearch::SearchEngineLiker($node->node_packages,$filtes,$Result,$display);
                    } else {
                        new core\error($v->node_ref . " skipped because don't have dna-node.json", false);
                    }

                }
            }
            if (count($nextStep) !== 0) {
                $Result=ToolNodeSearch::SearchEngine($nextStep,$filtes,$Result,$display);
            }
            return $Result;
        }

        /**
         * @param $node_packages
         * @param mixed|package_conf $filtes
         * @param array|NC\node_package $Result
         * @param bool $display
         * @return array|NC\node_package
         */
        public static function SearchEngineLiker($node_packages, $filtes, $Result,$display=true)
        {
            /**
             * @var NC\node_package $package
             */
            foreach ($node_packages as $i => $package) {
                if ($filtes->match($package->node_package_desc)) {
                    array_push($Result, $package);
                    if ($display)
                    new core\success("Search index : " . (count($Result)) . ") form: ".$package->node_package_ref."\n" . $package->node_package_desc->renderingSearch()."\n");
                }
            }
            return $Result;
        }
    }
}