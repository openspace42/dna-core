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

    class ToolNodeSearch
    {
        /**
         * @param $listOfNode
         * @param mixed|package_conf $filtes
         * @param array|NC\node_package $Result
         * @return array|NC\node_package
         */
        public static function SearchEngine($listOfNode,$filtes,$Result)
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
                        $Result=ToolNodeSearch::SearchEngineLiker($node->node_packages,$filtes,$Result);
                    } else {
                        new core\error($v->node_ref . " skipped because don't have dna-node.json", false);
                    }
                } else if ($v->node_type === "file_system") {
                    //future

                }
            }
            if (count($nextStep) !== 0) {
                $Result=ToolNodeSearch::SearchEngine($nextStep,$filtes,$Result);
            }
            return $Result;
        }

        /**
         * @param $node_packages
         * @param mixed|package_conf $filtes
         * @param array|NC\node_package $Result
         * @return array|NC\node_package
         */
        public static function SearchEngineLiker($node_packages, $filtes, $Result)
        {
            /**
             * @var NC\node_package $package
             */
            foreach ($node_packages as $i => $package) {
                if ($filtes->match($package->node_package_desc)) {
                    array_push($Result, $package);
                    new core\success("Search index : " . (count($Result)) . ") \n" . $package->node_package_desc->renderingSearch());
                }
            }
            return $Result;
        }
    }
}