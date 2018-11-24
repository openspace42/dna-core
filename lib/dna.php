<?php



namespace dna\core {

    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";

    use dna\core\fileSystem as FS;
    use dna\core\package_conf as PKConf;

    class dna
    {
        private $_runpath = "";

        //region construct

        public function __construct($args, $dir)
        {
            $this->_runpath = $dir;
            $args = array_slice($args, 1);
            $buffer_args = array();
            $op_set = false;
            $op = "";
            while (count($args) != 0) {
                $arg = $args[0];
                if ($op == "") {
                    switch ($arg) {
                        case 'install':
                            $op = "i";
                            break;
                        case 'build':
                            $op = "b";
                            break;
                        case 'run':
                            $op = "r";
                            break;
                        case 'compile':
                            $op = "c";
                            break;
                        case 'conf':
                            $op = "co";
                            break;
                        case 'help':
                            $op = "h";
                            break;
                        default:
                            $op = "r";
                            array_push($buffer_args, $arg);
                            break;
                    }
                } else {
                    array_push($buffer_args, $arg);
                }
                $args = array_slice($args, 1);
            }
            if ($op == "") {
                $op = "r";
            } else if ($op == "i") {
                $this->install($buffer_args);
            } else if ($op == "b") {
                $this->build($buffer_args);
            } else if ($op == "r") {
                $this->run($buffer_args);
            } else if ($op == "c") {
                $this->compile($buffer_args);
            } else if ($op == "co") {
                $this->conf($buffer_args);
            } else if ($op == "h") {
                $this->help($buffer_args);
            } else {
                $this->error("no op select");
            }
        }
        //endregion

        //region messages

        private function install($args)
        {
            while (count($args) != 0) {
                $arg = $args[0];
                switch ($arg) {
                    case 'help':
                    case '--help':
                    case '-h':
                    case 'h':
                        $this->install_Help();
                        break;
                    default:
                        $this->error("dna install don't have [\"" . $arg . "\"] argument, please user dna install -h for see the installation help", true);
                        break;
                }
                $args = array_slice($args, 1);
            }
            $this->install_worker();
        }

        private function install_Help()
        {
            echo "'dna install' is used to solve all dependencies required by the package\nusage:\n  dna install\t\t\t\t\t: solve all the dependencies\n  dna install [arguments]\t\t\t: to specify arguments\n  dna install < help | --help | -h | h >\t: for see a help message\n";
        }

        private function error($s, $e = true)
        {
            $s = "\033[38;2;255;179;186m[ERROR]: " . $s . "\033[39m\n";
            if ($e) {
                die($s);
            } else {
                echo $s;
            }

        }

        private function install_worker()
        {
            //0    echo "'dna install' is used to solve all dependencies required by the package\nusage:\n  dna install\t\t\t\t\t: solve all the dependencies\n  dna install [arguments]\t\t\t: to specify arguments\n  dna install < help | --help | -h | h >\t\t: for see a help message\n";
        }

        private function build($args)
        {
            while (count($args) != 0) {
                $arg = $args[0];
                switch ($arg) {
                    case 'help':
                    case '--help':
                    case '-h':
                    case 'h':
                        $this->build_Help();
                        break;
                    default:
                        $this->error("dna build don't have [\"" . $arg . "\"] argument, please user dna build -h for see the building help", true);
                        break;
                }
                $args = array_slice($args, 1);
            }
            $this->build_worker();
        }
        //endregion

        //region install

        private function build_Help()
        {
            echo "'dna build' is used to build the package\nusage:\n  dna build\t\t\t\t\t: build the package\n  dna build [arguments]\t\t\t\t: to specify arguments\n  dna build < help | --help | -h | h >\t\t: for see a help message\n";
        }

        private function build_worker()
        {
            //     echo "'dna install' is used to solve all dependencies required by the package\nusage:\n  dna install\t\t\t\t\t: solve all the dependencies\n  dna install [arguments]\t\t\t: to specify arguments\n  dna install < help | --help | -h | h >\t\t: for see a help message\n";
        }

        private function run($args)
        {
            $this->message("run: ");
            var_dump($args);
        }

        //endregion

        //region boild

        private function message($s)
        {
            $s = "" . $s . "\n";
            echo $s;
        }

        private function compile($args)
        {
            $this->message("compile: ");
            var_dump($args);
        }

        /**
         * @param $args
         */
        private function conf($args)
        {
            $cof_def = false;
            $package_name = false;
            $package_version = false;
            $package_uid = false;
            $package_author_grup = false;
            $package_author = false;
            $author_name = false;
            $author_surname = false;
            $author_nic = false;
            $author_link = false;
            $author_other = false;
            $package_add = false;
            $package_copyright = false;
            $package_licenseUrl = false;
            $package_require_license_acceptance = false;
            $package_websiteUrl = false;
            $package_docUrl = false;
            $package_release_note = false;
            $package_descripton = false;
            $package_iconUrl = false;
            $package_tag = false;
            while (count($args) != 0) {
                $arg = $args[0];
                switch ($arg) {
                    case 'help':
                    case '--help':
                    case '-h':
                    case 'h':
                        $this->conf_Help();
                        break;
                    case '--default':
                    case 'default':
                        if (!$cof_def) {
                            $cof_def = true;
                        } else {
                            $this->error("multiple tag default detected");
                        }
                        break;
                    case 'display':
                    case "--dispaly":
                        if (($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $this->error("you cant use " . $arg . " for same option");
                        } else {
                            $this->confDisplay($cof_def);
                            $cof_def = false;
                        }
                        break;
                    //region filter var
                    case '--package_name':
                    case 'package_name':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_version':
                    case 'package_version':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_version = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_uid':
                    case 'package_uid':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_uid = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_author_grup':
                    case 'package_author_grup':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_author_grup = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--add':
                    case 'add':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_add = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_copyright':
                    case 'package_copyright':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_copyright = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_licenseUrl':
                    case 'package_licenseUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_licenseUrl = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_require_license_acceptance':
                    case 'package_require_license_acceptance':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_require_license_acceptance = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_websiteUrl':
                    case 'package_websiteUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_websiteUrl = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_docUrl':
                    case 'package_docUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_docUrl = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_author':
                    case 'package_author':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_author = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_release_note':
                    case 'package_release_note':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_release_note = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_descripton':
                    case 'package_descripton':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_descripton = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_iconUrl':
                    case 'package_iconUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_iconUrl = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_tag':
                    case 'package_tag':
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_tag = true;
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    //endregion
                    case "--author_name":
                    case "author_name":
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_name = true;
                            } else {
                                $this->error("dna conf the insertion symmetry was not respected1");
                            }
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected2");
                        }
                        break;
                    case "--author_surname":
                    case "author_surname":
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_surname = true;
                            } else {
                                $this->error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_nic":
                    case "author_nic":
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_nic = true;
                            } else {
                                $this->error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_link":
                    case "author_link":
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_link = true;
                            } else {
                                $this->error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_other":
                    case "author_other":
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_other = true;
                            } else {
                                $this->error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            $this->error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    default:
                        if (!($package_name || $package_version || $package_uid || $package_author_grup || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_descripton || $package_iconUrl || $package_tag)) {
                            $this->error($arg . " is not among the valid options");
                        } else if ($package_name) {
                            $this->setConf("package_name", $arg, $cof_def);
                            $package_name = false;
                            $cof_def = false;
                        } else if ($package_version) {
                            $this->setConf("package_version", $arg, $cof_def);
                            $package_version = false;
                            $cof_def = false;
                        } else if ($package_uid) {
                            $this->setConf("package_uid", $arg, $cof_def);
                            $package_uid = false;
                            $cof_def = false;
                        } else if ($package_author_grup) {
                            $this->setConf("package_author_grup", $arg, $cof_def);
                            $package_author_grup = false;
                            $cof_def = false;
                        } else if ($author_name) {
                            $this->setConf("author_name", $arg, $cof_def);
                            $author_name = false;
                            $cof_def = false;
                        } else if ($author_surname) {
                            $this->setConf("author_surname", $arg, $cof_def);
                            $author_surname = false;
                            $cof_def = false;
                        } else if ($author_nic) {
                            $this->setConf("author_nic", $arg, $cof_def);
                            $author_nic = false;
                            $cof_def = false;
                        } else if ($author_link) {
                            $this->setConf("author_link", $arg, $cof_def);
                            $author_link = false;
                            $cof_def = false;
                        } else if ($author_other) {
                            $this->setConf("author_other", $arg, $cof_def);
                            $author_other = false;
                            $cof_def = false;
                        } else if ($package_copyright) {
                            $this->setConf("package_copyright", $arg, $cof_def);
                            $package_copyright = false;
                            $cof_def = false;
                        } else if ($package_licenseUrl) {
                            $this->setConf("package_licenseUrl", $arg, $cof_def);
                            $package_licenseUrl = false;
                            $cof_def = false;
                        } else if ($package_require_license_acceptance) {
                            if ($arg === "true") {
                                $arg = true;
                            } else if ($arg === "false") {
                                $arg = false;
                            } else {
                                $this->error("package_require_license_acceptance apcect only true or false");
                            }
                            $this->setConf("package_require_license_acceptance", $arg, $cof_def);
                            $package_require_license_acceptance = false;
                            $cof_def = false;
                        } else if ($package_websiteUrl) {
                            $this->setConf("package_websiteUrl", $arg, $cof_def);
                            $package_websiteUrl = false;
                            $cof_def = false;
                        } else if ($package_docUrl) {
                            $this->setConf("package_docUrl", $arg, $cof_def);
                            $package_docUrl = false;
                            $cof_def = false;
                        } else if ($package_release_note) {
                            $this->setConf("package_release_note", $arg, $cof_def);
                            $package_release_note = false;
                            $cof_def = false;
                        } else if ($package_descripton) {
                            $this->setConf("package_descripton", $arg, $cof_def);
                            $package_descripton = false;
                            $cof_def = false;
                        } else if ($package_iconUrl) {
                            $this->setConf("package_iconUrl", $arg, $cof_def);
                            $package_iconUrl = false;
                            $cof_def = false;
                        } else if ($package_tag) {
                            $this->setConf("package_tag", $arg, $cof_def);
                            $package_tag = false;
                            $cof_def = false;
                        } else if ($package_add) {
                            switch ($arg) {
                                case 'owner':
                                    $this->confAddOwner($cof_def);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                case 'node_extend':
                                    $this->confAddNodeExtend($cof_def);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                case 'depencies':
                                    $this->confAddDepencies($cof_def);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                case 'tag':
                                    $this->confAddTag($cof_def);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                default:
                                    $this->error($arg . " is not among the valid options");
                                    break;
                            }
                        }
                        break;
                }
                $args = array_slice($args, 1);
            }
            $this->build_worker();
        }

        //endregion

        private function conf_Help()
        {
            //echo "'dna build' is used to build the package\nusage:\n  dna build\t\t\t\t\t: build the package\n  dna build [arguments]\t\t\t\t: to specify arguments\n  dna build < help | --help | -h | h >\t\t: for see a help message\n";
        }

        private function confDisplay($g = false)
        {
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) $this->error("sorry but something went wrong");
            $this->message($pkconf->WithDefault()->renderDisplay(true));

        }

        private function InitDefaultConf()
        {
            $userDir = FS::getUserHomeDir();
            if (file_exists($userDir . "/.dna/default_conf.json")) {
                return new PKConf(FS::opneJson($userDir . "/.dna/default_conf.json"));
            } else {
                $res = $this->IO_get("was not found the " . $userDir . "/.dna/default_conf.json file, create one (y|yes/n|no)?");
                if ($res === "y" || $res === "yes") {
                    $pkconf = new PKConf(array());
                    FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
                    return $pkconf;
                } else {
                    $this->error("the creation of " . $userDir . "/.dna/default_conf.json has been canceled, exit ...");
                }
            }
        }

        private function IO_get($s, $n = true)
        {
            if (PHP_OS == 'WINNT') {
                $this->info($s, $n);
                return stream_get_line(STDIN, 1024, PHP_EOL);
            } else {
                $this->info($s, $n);
                return readline();
            }
        }

        //region Conf

        private function info($s, $n = true)
        {
            $s = "\033[38;2;186;225;255m" . $s . "\033[39m" . ($n ? "\n" : "");
            echo $s;
        }

        private function InitConf()
        {
            if (!file_exists($this->_runpath . "/conf.json")) {
                $res = $this->IO_get("was not found the conf.json file, create one (y|yes/n|no)?");
                if ($res === "y" || $res === "yes") {
                    $res = $this->IO_get("start with local default conf (y|yes/n|no)?");
                    if ($res === "y" || $res === "yes") {
                        $pkconf = $this->InitDefaultConf();
                        FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
                        return $pkconf;
                    } else {
                        $pkconf = new PKConf(array());
                        FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
                        return $pkconf;
                    }
                } else {
                    $this->error("the creation of conf.json has been canceled, exit ...");
                }
            } else {
                return new PKConf(FS::opneJson($this->_runpath . "/conf.json"));
            }
        }

        private function setConf($n, $v, $g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) $this->error("sorry but something went wrong");
            if ($n == "author_name" || $n == "author_surname" || $n == "author_nic" || $n == "author_link" || $n == "author_other")
                $pkconf->package_author->$n = $v;
            else
                $pkconf->$n = $v;
            if ($g) $this->success($n . " succesful set on " . $userDir . "/.dna/default_conf.json");
            else $this->success($n . " succesful set on " . $this->_runpath . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
        }

        private function success($s)
        {
            $s = "\033[38;2;186;255;201m" . $s . "\033[39m\n";
            echo $s;
        }

        private function confAddOwner($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) $this->error("sorry but something went wrong");
            $on = $this->IO_get("owner_name (empty of null):");
            $os = $this->IO_get("owner_surname (empty of null):");
            $oni = null;
            while ($oni == null)
                $oni = $this->IO_get("owner_nic (obligatory):");
            $ol = $this->IO_get("owner_link (empty of null):");
            $oo = $this->IO_get("owner_other (empty of null):");
            $pkconf->addOwner($on, $os, $oni, $ol, $oo);
            if ($g) $this->success("Owner succesful add on " . $userDir . "/.dna/default_conf.json");
            else $this->success("Owner succesful add on " . $this->_runpath . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
        }

        private function confAddNodeExtend($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) $this->error("sorry but something went wrong");
            $ref = null;
            while ($ref == null)
                $ref = $this->IO_get("node_ref (obligatory):");
            $tp = null;
            while ($tp != "1" || $tp != "2") {
                $tp = $this->IO_get("node_type ([ 1 | empty ] http , [2] file_system):");
                if ($tp == "") $tp = "1";
            }
            $pkconf->addNodeExtend($ref, $tp);
            if ($g) $this->success("Owner succesful add on " . $userDir . "/.dna/default_conf.json");
            else $this->success("Owner succesful add on " . $this->_runpath . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
        }

        private function confAddDepencies($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) $this->error("sorry but something went wrong");
            $this->info("Welcome to dna search depencies tool");
            $this->message("options: s) for search,  a) add filter,  import <search index>) to import a depencies, h) display help,  e) to exit");
            $in = "";
            while ($in != "e" && $in != "exit") {
                $in = $this->IO_get("option >", false);

            }
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
        }

        private function confAddTag($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            $tag = $this->IO_get("tag: (empty for cancel):");
            if ($tag != "")
                $pkconf->addTag($tag);
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->_runpath . "/conf.json", $pkconf->simplify());
        }

        //endregion

        private function help($args)
        {
            $this->message("help: ");
            var_dump($args);
        }

        //region other function

        private function warning($s)
        {
            $s = "\033[38;2;255;255;186m" . $s . "\033[39m\n";
            echo $s;
        }
        //endregion
    }
}