<?php

namespace dna\core {
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/dna_messages.php";
    require_once __DIR__ . "/ToolNodeSearch.php";
    require_once __DIR__ . "/network.php";


    use dna\core\fileSystem as FS;
    use dna\core as core;
    use dna\core\ToolNodeSearch as TNS;
    use dna\core\network as NET;
    use dna\core\Zip as Zip;

    class dna_install
    {
        private $run_path = "";
        /**
         * @var package_conf
         */
        private $PkConf = null;

        public function __construct($path, $PkCong, $new = false, $msp = "")
        {
            $msp = core\success::prepare($msp);
            $this->run_path = $path;
            $this->PkConf = $PkCong;
            if ($new) {
                FS::deleteFolder($this->run_path . "/Dependencies");
            }
            /**
             * @var package_conf $dep
             */
            foreach ($this->PkConf->package_dependencies as $i => $dep) {
                if (!file_exists($this->run_path . "/Dependencies/"))
                    mkdir($this->run_path . "/Dependencies/");
                if (file_exists($this->run_path . "/Dependencies/" . $dep->package_uid)) {
                    new core\message($msp, false);
                    new core\warning($dep->package_uid . "  is already installed");
                    if (file_exists($this->run_path . "/Dependencies/" . $dep->package_uid . "/conf.json"))
                        new dna_install($path . "/Dependencies/" . $dep->package_uid . "/", new package_conf(FS::openJson($this->run_path . "/Dependencies/" . $dep->package_uid . "/conf.json")), false, "  │      " . $msp);
                    else {
                        new core\message($msp, false);
                        new core\error("Error on cloning " . $dep->package_uid . " on " . $this->run_path . "/Dependencies/" . $dep->package_uid . " Exiting...");
                    }
                } else {
                    $tempResult = TNS::SearchEngine($this->PkConf->package_node_extend, $dep, array(), false);
                    $tempPath = sys_get_temp_dir() . "/" . FS::generateRandomString(10) . ".zip";
                    if (!isset($tempResult[0])) {
                        new core\message($msp, false);
                        new core\error("I cant found :\n" . $dep->renderingSearch() . "\n");
                    }
                    if (NET::URLIsValid($tempResult[0]->node_package_ref)) {
                        NET::Download($tempPath, $tempResult[0]->node_package_ref);
                    } else {
                        FS::CopyFile($tempPath, $tempResult[0]->node_package_ref);
                    }
                    mkdir($this->run_path . "/Dependencies/" . $dep->package_uid);
                    $status = Zip::UnZip($tempPath, $this->run_path . "/Dependencies/" . $dep->package_uid);
                    if (!$status) {
                        new core\message($msp, false);
                        new core\error("Error on cloning " . $dep->package_uid . " on " . $this->run_path . "/Dependencies/" . $dep->package_uid . " Exiting...");
                    } else {
                        new core\message($msp, false);
                        new core\success($dep->package_uid . " on " . $this->run_path . "/Dependencies/" . $dep->package_uid . " are installed!");
                        if (file_exists($this->run_path . "/Dependencies/" . $dep->package_uid . "/conf.json")) {
                          //  str_replace("└", "├", "$msp");
                            new dna_install($path . "/Dependencies/" . $dep->package_uid . "/", new package_conf(FS::openJson($this->run_path . "/Dependencies/" . $dep->package_uid . "/conf.json")), false, "  │      " . $msp);
                        } else {
                            new core\message($msp, false);
                            new core\error("Error on cloning " . $dep->package_uid . " on " . $this->run_path . "/Dependencies/" . $dep->package_uid . " Exiting...");
                        }
                    }
                }
            }
        }
    }

}