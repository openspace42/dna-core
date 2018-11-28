<?php

namespace dna\core {
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/dna_messages.php";
    require_once __DIR__ . "/ToolAddDependencies.php";

    use dna\core\fileSystem as FS;
    use dna\core\package_conf as PKConf;
    use dna\core as core;

    class dna_conf
    {
        private $run_path = "";

        public function __construct($args, $dir)
        {
            $this->run_path = $dir;

            $cof_def = false;
            $package_name = false;
            $package_version = false;
            $package_uid = false;
            $package_author_group = false;
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
            $package_description = false;
            $package_iconUrl = false;
            $package_tag = false;
            while (count($args) != 0) {
                $arg = $args[0];
                /*

                  help build add default display package_name package_version package_uid package_author_group package_copyright package_licenseUrl
                  package_require_license_acceptance package_websiteUrl package_docUrl package_author package_release_note package_description
                  package_iconUrl package_tag

                  sub: package_author
                  author_name author_surname author_nic author_link author_other

                  sub: add
                  owner node_extend dependencies tag

                 */
                switch ($arg) {
                    case 'help':
                    case '--help':
                    case '-h':
                    case 'h':
                        $this->conf_Help();
                        break;
                    case 'build':
                        $this->conf_Build();
                        break;
                    case '--default':
                    case 'default':
                        if (!$cof_def) {
                            $cof_def = true;
                        } else {
                            new core\error("multiple tag default detected");
                        }
                        break;
                    case 'display':
                    case "--display":
                        if (($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            new core\error("you cant use " . $arg . " for same option");
                        } else {
                            $this->confDisplay($cof_def);
                            $cof_def = false;
                        }
                        break;
                    case '--package_name':
                    case 'package_name':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_version':
                    case 'package_version':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_version = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_uid':
                    case 'package_uid':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_uid = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_author_group':
                    case 'package_author_group':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_author_group = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--add':
                    case 'add':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_add = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_copyright':
                    case 'package_copyright':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_copyright = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_licenseUrl':
                    case 'package_licenseUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_licenseUrl = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_require_license_acceptance':
                    case 'package_require_license_acceptance':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_require_license_acceptance = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_websiteUrl':
                    case 'package_websiteUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_websiteUrl = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_docUrl':
                    case 'package_docUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_docUrl = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_author':
                    case 'package_author':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_author = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_release_note':
                    case 'package_release_note':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_release_note = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_description':
                    case 'package_description':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_description = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_iconUrl':
                    case 'package_iconUrl':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_iconUrl = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case '--package_tag':
                    case 'package_tag':
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_tag = true;
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    //endregion
                    case "--author_name":
                    case "author_name":
                        if (!($package_name || $package_version || $package_uid || $package_author_group || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_name = true;
                            } else {
                                new core\error("dna conf the insertion symmetry was not respected1");
                            }
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected2");
                        }
                        break;
                    case "--author_surname":
                    case "author_surname":
                        if (!($package_name || $package_version || $package_uid || $package_author_group || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_surname = true;
                            } else {
                                new core\error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_nic":
                    case "author_nic":
                        if (!($package_name || $package_version || $package_uid || $package_author_group || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_nic = true;
                            } else {
                                new core\error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_link":
                    case "author_link":
                        if (!($package_name || $package_version || $package_uid || $package_author_group || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_link = true;
                            } else {
                                new core\error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    case "--author_other":
                    case "author_other":
                        if (!($package_name || $package_version || $package_uid || $package_author_group || !$package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            $package_name = false;
                            if (!($author_name || $author_surname || $author_nic || $author_link || $author_other)) {
                                $author_other = true;
                            } else {
                                new core\error("dna conf the insertion symmetry was not respected");
                            }
                        } else {
                            new core\error("dna conf the insertion symmetry was not respected");
                        }
                        break;
                    default:
                        if (!($package_name || $package_version || $package_uid || $package_author_group || $package_author || $package_add || $package_copyright || $package_licenseUrl || $package_require_license_acceptance || $package_websiteUrl || $package_docUrl || $package_release_note || $package_description || $package_iconUrl || $package_tag)) {
                            new core\error($arg . " is not among the valid options");
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
                        } else if ($package_author_group) {
                            $this->setConf("package_author_group", $arg, $cof_def);
                            $package_author_group = false;
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
                                new core\error("package_require_license_acceptance acceptance only true or false");
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
                        } else if ($package_description) {
                            $this->setConf("package_description", $arg, $cof_def);
                            $package_description = false;
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
                                case 'dependencies':
                                    $pkconf = null;
                                    if ($cof_def) $pkconf = $this->InitDefaultConf();
                                    else $pkconf = $this->InitConf()->WithDefault();
                                    new PKConf\ToolAddDependencies($this->run_path, $pkconf, $cof_def);
                                    unset($pkconf);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                case 'tag':
                                    $this->confAddTag($cof_def);
                                    $cof_def = false;
                                    $package_add = false;
                                    break;
                                default:
                                    new core\error($arg . " is not among the valid options");
                                    break;
                            }
                        }
                        break;
                }
                $args = array_slice($args, 1);
            }
        }

        //endregion

        private function conf_Help()
        {
        }

        private function conf_Build()
        {
            $pkconf = $this->InitConf()->WithDefault();
            $pkconf->package_release_date = date("Y-m-d H:i:s");
            if ($pkconf->generateUID() === false) {
                new core\error("it is not possible to build conf.build.json");
            } else {
                $pkconf->package_uid = $pkconf->generateUID();
                FS::writeAllPath($this->run_path . "/conf.build.json", $pkconf->simplify());
            }
        }

        private function InitConf()
        {
            if (!file_exists($this->run_path . "/conf.json")) {
                $res = FS::IO_get("was not found the conf.json file, create one (y|yes/n|no)?");
                if ($res === "y" || $res === "yes") {
                    $res = FS::IO_get("start with local default conf (y|yes/n|no)?");
                    if ($res === "y" || $res === "yes") {
                        $pkconf = $this->InitDefaultConf();
                        FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
                        return $pkconf;
                    } else {
                        $pkconf = new PKConf(array());
                        FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
                        return $pkconf;
                    }
                } else {
                    new core\error("the creation of conf.json has been canceled, exit ...");
                    return null;
                }
            } else {
                return new PKConf(FS::openJson($this->run_path . "/conf.json"));
            }
        }


        //region Conf

        private function InitDefaultConf()
        {
            $userDir = FS::getUserHomeDir();
            if (!file_exists($userDir . "/.dna/")) {
                $res = FS::IO_get("The " . $userDir . "/.dna/ folder was not found, create one (y|yes/n|no)?");
                if ($res === "y" || $res === "yes") {
                    mkdir($userDir . "/.dna");
                } else {
                    new core\error("the creation of " . $userDir . "/.dna/ has been canceled, exit ...");
                }
            }
            if (file_exists($userDir . "/.dna/default_conf.json")) {
                return new PKConf(FS::openJson($userDir . "/.dna/default_conf.json"));
            } else {
                $res = FS::IO_get("was not found the " . $userDir . "/.dna/default_conf.json file, create one (y|yes/n|no)?");
                if ($res === "y" || $res === "yes") {
                    $pkconf = new PKConf(array());
                    FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
                    return $pkconf;
                } else {
                    new core\error("the creation of " . $userDir . "/.dna/default_conf.json has been canceled, exit ...");
                    return null;
                }
            }
        }

        private function confDisplay($g = false)
        {
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) new core\error("sorry but something went wrong");
            new core\message($pkconf->WithDefault()->renderDisplay(true));

        }

        private function setConf($n, $v, $g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) new core\error("sorry but something went wrong");
            if ($n == "author_name" || $n == "author_surname" || $n == "author_nic" || $n == "author_link" || $n == "author_other")
                $pkconf->package_author->$n = $v;
            else
                $pkconf->$n = $v;
            if ($g) new core\success($n . " successful set on " . $userDir . "/.dna/default_conf.json");
            else new core\success($n . " successful set on " . $this->run_path . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
        }


        private function confAddOwner($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) new core\error("sorry but something went wrong");
            $on = FS::IO_get("owner_name (empty of null):");
            $os = FS::IO_get("owner_surname (empty of null):");
            $oni = null;
            while ($oni == null)
                $oni = FS::IO_get("owner_nic (obligatory):");
            $ol = FS::IO_get("owner_link (empty of null):");
            $oo = FS::IO_get("owner_other (empty of null):");
            $pkconf->addOwner($on, $os, $oni, $ol, $oo);
            if ($g) new core\success("Owner successful add on " . $userDir . "/.dna/default_conf.json");
            else new core\success("Owner successful add on " . $this->run_path . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
        }

        private function confAddNodeExtend($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            if ($pkconf == null) new core\error("sorry but something went wrong");
            $ref = null;
            while ($ref == null)
                $ref = FS::IO_get("node_ref (obligatory):");
            $tp = null;
            while ($tp != "1" || $tp != "2") {
                $tp = FS::IO_get("node_type ([ 1 | empty ] http , [2] file_system):");
                if ($tp == "") $tp = "1";
            }
            $pkconf->addNodeExtend($ref, $tp);
            if ($g) new core\success("Owner successful add on " . $userDir . "/.dna/default_conf.json");
            else new core\success("Owner successful add on " . $this->run_path . "/conf.json");
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
        }

        private function confAddTag($g = false)
        {
            $userDir = FS::getUserHomeDir();
            $pkconf = null;
            if ($g) $pkconf = $this->InitDefaultConf();
            else $pkconf = $this->InitConf();
            $tag = FS::IO_get("tag: (empty for cancel):");
            if ($tag != "")
                $pkconf->addTag($tag);
            if ($g) FS::writeAllPath($userDir . "/.dna/default_conf.json", $pkconf->simplify());
            else FS::writeAllPath($this->run_path . "/conf.json", $pkconf->simplify());
        }
    }
}