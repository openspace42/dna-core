<?php

namespace dna\core {

    require_once __DIR__ . "/author.php";
    require_once __DIR__ . "/owner.php";
    require_once __DIR__ . "/node_extend.php";
    require_once __DIR__ . "/fileSystem.php";

    use dna\core\package_conf as PkConf;
    use dna\core\fileSystem as FS;

    class package_conf
    {
        public $package_name = "";
        public $package_version = "";
        public $package_uid = "";
        public $package_author;
        public $package_author_grup = "";
        public $package_owners = array();
        public $package_copyright = "";
        public $package_licenseUrl = "";
        public $package_require_license_acceptance = false;
        public $package_websiteUrl = "";
        public $package_docUrl = "";
        public $package_node_extend = array();
        public $package_release_note = "";
        public $package_descripton = "";
        public $package_iconUrl = "";
        public $package_tag = "";
        public $package_depencies = array();
        public $package_release_date = "";
        private $package_tags = array();


        public function __construct($jd)
        {
            if (array_key_exists("package_name", $jd)) {
                $this->package_name = $jd['package_name'];
            }

            if (array_key_exists("package_version", $jd)) {
                $this->package_version = $jd['package_version'];
            }

            if (array_key_exists("package_uid", $jd)) {
                $this->package_uid = $jd['package_uid'];
            }

            if (array_key_exists("package_author", $jd)) {
                $this->package_author = $jd['package_author'];
            }

            if (array_key_exists("package_author_grup", $jd)) {
                $this->package_author_grup = $jd['package_author_grup'];
            }

            if (array_key_exists("package_copyright", $jd)) {
                $this->package_copyright = $jd['package_copyright'];
            }

            if (array_key_exists("package_licenseUrl", $jd)) {
                $this->package_licenseUrl = $jd['package_licenseUrl'];
            }

            if (array_key_exists("package_require_license_acceptance", $jd)) {
                $this->package_require_license_acceptance = $jd['package_require_license_acceptance'];
            }

            if (array_key_exists("package_websiteUrl", $jd)) {
                $this->package_websiteUrl = $jd['package_websiteUrl'];
            }

            if (array_key_exists("package_docUrl", $jd)) {
                $this->package_docUrl = $jd['package_docUrl'];
            }

            if (array_key_exists("package_release_note", $jd)) {
                $this->package_release_note = $jd['package_release_note'];
            }

            if (array_key_exists("package_descripton", $jd)) {
                $this->package_descripton = $jd['package_descripton'];
            }

            if (array_key_exists("package_iconUrl", $jd)) {
                $this->package_iconUrl = $jd['package_iconUrl'];
            }

            if (array_key_exists("package_release_date", $jd)) {
                $this->package_release_date = $jd['package_release_date'];
            }

            //author
            $this->package_author = new package_conf\author();
            $t_author = array();
            if (array_key_exists("package_author", $jd)) {
                $t_author = $jd['package_author'];
            }

            if (array_key_exists("author_name", $t_author)) {
                $this->package_author->author_name = $t_author['author_name'];
            }

            if (array_key_exists("author_surname", $t_author)) {
                $this->package_author->author_surname = $t_author['author_surname'];
            }

            if (array_key_exists("author_nic", $t_author)) {
                $this->package_author->author_nic = $t_author['author_nic'];
            }

            if (array_key_exists("author_link", $t_author)) {
                $this->package_author->author_link = $t_author['author_link'];
            }

            if (array_key_exists("author_other", $t_author)) {
                $this->package_author->author_other = $t_author['author_other'];
            }

            //owners
            $this->package_owners = array();
            $t_owners = array();
            if (array_key_exists("package_owners", $jd)) {
                $t_owners = $jd['package_owners'];
            }

            foreach ($t_owners as $key => $value) {
                $t_owner = new package_conf\owner();
                if (array_key_exists("owner_name", $value)) {
                    $t_owner->owner_name = $value['owner_name'];
                }

                if (array_key_exists("owner_surname", $value)) {
                    $t_owner->owner_surname = $value['owner_surname'];
                }

                if (array_key_exists("owner_nic", $value)) {
                    $t_owner->owner_nic = $value['owner_nic'];
                }

                if (array_key_exists("owner_link", $value)) {
                    $t_owner->owner_link = $value['owner_link'];
                }

                if (array_key_exists("owner_other", $value)) {
                    $t_owner->owner_other = $value['owner_other'];
                }

                array_push($this->package_owners, $t_owner);
            }
            //node_extend
            $this->package_node_extend = array();
            $node_extends = array();
            if (array_key_exists("package_node_extend", $jd)) {
                $node_extends = $jd['package_node_extend'];
            }

            foreach ($node_extends as $key => $value) {
                $node_extend = new package_conf\node_extend();
                if (array_key_exists("node_ref", $value)) {
                    $node_extend->node_ref = $value['node_ref'];
                }

                if (array_key_exists("node_type", $value)) {
                    $node_extend->node_type = $value['node_type'];
                }

                array_push($this->package_node_extend, $node_extend);
            }
            //tags
            if (array_key_exists("package_tag", $jd)) {
                $this->package_tag = $jd['package_tag'];
            }

            while ($this->package_tag != str_replace("  ", " ", $this->package_tag)) {
                $this->package_tag = str_replace("  ", " ", $this->package_tag);
            }

            while ($this->package_tag != str_replace(", ", ",", $this->package_tag)) {
                $this->package_tag = str_replace(", ", ",", $this->package_tag);
            }

            $this->package_tags = explode(",", $this->package_tag);

        }

        public function simplify()
        {
            return FS::JsonStringRemveEmpty(json_encode($this));
        }

        public function addOwner($on, $os, $oni, $ol, $oo)
        {
            $t_owner = new package_conf\owner();
            $t_owner->owner_name = $on;
            $t_owner->owner_surname = $os;
            $t_owner->owner_nic = $oni;
            $t_owner->owner_link = $ol;
            $t_owner->owner_other = $oo;
            array_push($this->package_owners, $t_owner);
        }

        public function addNodeExtend($ref, $tp)
        {
            $node_extend = new package_conf\node_extend();
            $node_extend->node_ref = $ref;
            if ($tp === "1") $tp = "http";
            else if ($tp === "2") $tp = "file_system";
            else die("dna\core\package_conf::addNodeExtend -> " . $tp . " not valid");
            $node_extend->node_type = $tp;
            array_push($this->package_node_extend, $node_extend);
        }

        public function addTag($tag)
        {
            array_push($this->$this->package_tags, $tag);
            $this->tagRegen();
        }

        private function tagRegen()
        {
            $t = "";
            foreach ($this->package_tags as $key => $value) {
                $t .= ($t = "" ? "" : ",") . $value;
            }
            $this->package_tag = $t;
        }

        public function WithDefault()
        {
            $bas = clone $this;
            $def = null;
            $userDir = FS::getUserHomeDir();
            if (file_exists($userDir . "/.dna/default_conf.json")) {
                $def = new PkConf(FS::opneJson($userDir . "/.dna/default_conf.json"));
            }
            if ($def == null) return $bas;
            else {
                if ($bas->package_name === "" && $def->package_name !== "") $bas->package_name = $def->package_name;
                if ($bas->package_version === "" && $def->package_version !== "") $bas->package_version = $def->package_version;
                if ($bas->package_uid === "" && $def->package_uid !== "") $bas->package_uid = $def->package_uid;
                if ($bas->package_author_grup === "" && $def->package_author_grup !== "") $bas->package_author_grup = $def->package_author_grup;
                if ($bas->package_copyright === "" && $def->package_copyright !== "") $bas->package_copyright = $def->package_copyright;
                if ($bas->package_licenseUrl === "" && $def->package_licenseUrl !== "") $bas->package_licenseUrl = $def->package_licenseUrl;
                if ($bas->package_require_license_acceptance === "" && $def->package_require_license_acceptance !== "") $bas->package_require_license_acceptance = $def->package_require_license_acceptance;
                if ($bas->package_websiteUrl === "" && $def->package_websiteUrl !== "") $bas->package_websiteUrl = $def->package_websiteUrl;
                if ($bas->package_docUrl === "" && $def->package_docUrl !== "") $bas->package_docUrl = $def->package_docUrl;
                if ($bas->package_release_note === "" && $def->package_release_note !== "") $bas->package_release_note = $def->package_release_note;
                if ($bas->package_descripton === "" && $def->package_descripton !== "") $bas->package_descripton = $def->package_descripton;
                if ($bas->package_iconUrl === "" && $def->package_iconUrl !== "") $bas->package_iconUrl = $def->package_iconUrl;
                if ($bas->package_tag === "" && $def->package_tag !== "") $bas->package_tag = $def->package_tag;
                if ($bas->package_release_date === "" && $def->package_release_date !== "") $bas->package_release_date = $def->package_release_date;

                //package_author
                if ($bas->package_author != null) {
                    if ($def->package_author != null) {
                        if ($bas->package_author->author_name === "" && $def->package_author->author_name !== "") $bas->package_author->author_name = $def->package_author->author_name;
                        if ($bas->package_author->author_surname === "" && $def->package_author->author_surname !== "") $bas->package_author->author_surname = $def->package_author->author_surname;
                        if ($bas->package_author->author_nic === "" && $def->package_author->author_nic !== "") $bas->package_author->author_nic = $def->package_author->author_nic;
                        if ($bas->package_author->author_link === "" && $def->package_author->author_link !== "") $bas->package_author->author_link = $def->package_author->author_link;
                        if ($bas->package_author->author_other === "" && $def->package_author->author_other !== "") $bas->package_author->author_other = $def->package_author->author_other;
                    }
                } else {
                    if ($def->package_author != null) $bas->package_author = $def->package_author;
                }

                // package_node_extend
                if ($bas->package_node_extend != null) {
                    if ($def->package_node_extend != null) {
                        foreach ($def->package_node_extend as $i => $v) {
                            $found = false;
                            foreach ($bas->package_node_extend as $ii => $vv)
                                if ($v->node_ref === $vv->node_ref && $v->node_type === $vv->node_type) $found = true;
                            if (!$found) array_push($bas->package_node_extend, $v);
                        }
                    }
                } else {
                    if ($def->package_node_extend != null) $bas->package_node_extend = $def->package_node_extend;
                }

                // package_owners
                if ($bas->package_owners != null) {
                    if ($def->package_owners != null) {
                        foreach ($def->package_owners as $i => $v) {
                            $found = false;
                            foreach ($bas->package_owners as $ii => $vv)
                                if ($v->owner_name === $vv->owner_name && $v->owner_surname === $vv->owner_surname && $v->owner_nic === $vv->owner_nic && $v->owner_link === $vv->owner_link && $v->owner_other === $vv->owner_other) $found = true;
                            if (!$found) array_push($bas->package_owners, $v);
                        }
                    }
                } else {
                    if ($def->package_owners != null) $bas->package_owners = $def->package_owners;
                }

                return $bas;

            }
        }


        public function renderDisplay($all = false)
        {
            /*
                Package:
                  Name: package_name      Version: pakege_version
                  Uid: package_name_pakege_version@pakege_author_grup.author_nic
                  Group: pakege_author_grup

                  Release note: package_release_note
                  Release data: package_release_date
                  Description:
                     package_descripton

                  Tegs: package_tag

                  Icon: package_iconUrl
                  Author :
                     Name: author_name     Surname: author_surname
                     Nic: author_nic
                     Link: author_link
                     Other: author_other

                  Owners:
                     N) Name: owner_name, Surname: owner_surname, Nic : owner_nic, Link: owner_link, Other: owner_other

                  Copyright : package_copyright
                  Licence: package_licenseUrl
                  License acceptance: Yes|No
                  Website: package_websiteUrl
                  Doc: package_docUrl

                  Node Extend:
                     N) Type: node_type, Reference: node_ref

                  Depencies:
                     N) Name: package_name, Version: pakege_version, Uid: package_name_pakege_version@pakege_author_grup.author_nic
             */
            $Des = "";
            foreach (explode('\n', $this->package_descripton) as $i => $v) $Des .= "      " . $v . "\n";
            $Owners = "";
            foreach ($this->package_owners as $i => $v)
                $Owners .= "      " . $i . ")\t" . "Name: " . $v->owner_name . ", Surname: " . $v->owner_surname . ", Nic : " . $v->owner_nic . ", Link: " . $v->owner_link . ", Other: " . $v->owner_other . "\n";
            $Ne = "";
            foreach ($this->package_node_extend as $i => $v)
                $Ne .= "      " . $i . ")\t" . "Type: " . $v->node_type . ", Reference: " . $v->node_ref . "\n";
            $de = "";
            foreach ($this->package_depencies as $i => $v)
                $de .= "      " . $i . ")\t" . "Name: " . $v->package_name . ", Version: " . $v->package_version . ", Uid: " . $v->package_uid . "\n";
            if ($all) {
                return "Package:\n" .
                    "   Name: " . $this->package_name . ",          Version: " . $this->package_version . "\n" .
                    "   Uid: " . $this->package_uid . "\n" .
                    "   Group: " . $this->package_author_grup . "\n\n" .
                    "   Release note: " . $this->package_release_note . "\n" .
                    "   Release date: " . $this->package_release_date . "\n" .
                    "   Description:\n" . $Des . "\n" .
                    "   Tegs: " . $this->package_tag . "\n" .
                    "   Icon: " . $this->package_iconUrl . "\n\n" .
                    "   Author:\n" .
                    "      Name: " . $this->package_author->author_name . "      Surname: " . $this->package_author->author_surname . "\n" .
                    "      Nic: " . $this->package_author->author_nic . "\n" .
                    "      Link: " . $this->package_author->author_link . "\n" .
                    "      Other: " . $this->package_author->author_other . "\n\n" .
                    "   Owners: " . "\n" . $Owners . "\n\n" .
                    "   Copyright: " . $this->package_copyright . "\n" .
                    "   Licence: " . $this->package_licenseUrl . "\n" .
                    "   License acceptance: " . ($this->package_require_license_acceptance ? "Yes" : "No") . "\n" .
                    "   Website: " . $this->package_websiteUrl . "\n" .
                    "   Doc: " . $this->package_docUrl . "\n\n" .
                    "   Node Extend: \n" . $Ne . "\n" .
                    "   Depencies: \n" . $Ne . "\n";
            }
        }
    }
}