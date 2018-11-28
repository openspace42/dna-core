<?php

namespace dna\core {

    require_once __DIR__ . "/author.php";
    require_once __DIR__ . "/owner.php";
    require_once __DIR__ . "/node_extend.php";
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/network.php";

    use dna\core\package_conf as PkConf;
    use dna\core\fileSystem as FS;
    use dna\core\network as NET;
    use dna\core as core;

    /**
     * Class package_conf represents a conf.json
     * @package dna\core
     */
    class package_conf
    {
        /**
         * @var string name
         */
        public $package_name = "";
        /**
         * @var string version
         */
        public $package_version = "";
        /**
         * @var string uid [IS AUTO GENERATED] {package_name}_{package_version}@{package_author_group}.{package_author->author_nic}
         */
        public $package_uid = "";
        /**
         * @var PkConf\author is a instance of author
         */
        public $package_author;
        /**
         * @var string it is the group that releases the package
         */
        public $package_author_group = "";
        /**
         * @var array a list of owners
         */
        public $package_owners = array();
        /**
         * @var string copyright
         */
        public $package_copyright = "";
        /**
         * @var string url of licence
         */
        public $package_licenseUrl = "";
        /**
         * @var bool if true dna require a acceptance of licence
         */
        public $package_require_license_acceptance = false;
        /**
         * @var string a website
         */
        public $package_websiteUrl = "";
        /**
         * @var string a link of documentation
         */
        public $package_docUrl = "";
        /**
         * @var array a list of extended nodes
         */
        public $package_node_extend = array();
        /**
         * @var string release note
         */
        public $package_release_note = "";
        /**
         * @var string description of package
         */
        public $package_description = "";
        /**
         * @var string PNG icon( 32px X 32px ) url
         */
        /*
        ################
        ################
        ################
        ################
        ################
        ################
        ################
        ################
        */
        public $package_iconUrl = "";
        /**
         * @var mixed|string a tags formatted like [tag1,teg2,teg3,sub tag4,tag4]
         */
        public $package_tag = "";
        /**
         * @var array a list of dependencies
         */
        public $package_dependencies = array();
        /**
         * @var string  release date [IS AUTO GENERATED]
         */
        public $package_release_date = "";
        /**
         * @var array an array of tags
         */
        private $package_tags = array();


        /**
         * package_conf constructor.
         * @param $jd
         */
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

            if (array_key_exists("package_author_group", $jd)) {
                $this->package_author_group = $jd['package_author_group'];
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

            if (array_key_exists("package_description", $jd)) {
                $this->package_description = $jd['package_description'];
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
            if ($this->package_tag!=="")
            $this->package_tags = explode(",", $this->package_tag);
            else
                $this->package_tags=array();

            //dependencies

            if (array_key_exists("package_dependencies", $jd)) {
                foreach ( $jd['package_dependencies'] as $pk)
                    array_push($this->package_dependencies, new package_conf($pk));
            }


        }

        /**
         * @return false|string
         */
        public function simplify()
        {
            return FS::JsonStringRemoveEmpty(json_encode($this));
        }

        /**
         * @param $on
         * @param $os
         * @param $oni
         * @param $ol
         * @param $oo
         */
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

        /**
         * @param $ref
         * @param $tp
         */
        public function addNodeExtend($ref, $tp)
        {
            $node_extend = new PkConf\node_extend();
            $node_extend->node_ref = $ref;
            if ($tp === "1") $tp = "http";
            else if ($tp === "2") $tp = "file_system";
            else die("dna\core\package_conf::addNodeExtend -> " . $tp . " not valid");
            $node_extend->node_type = $tp;
            array_push($this->package_node_extend, $node_extend);
        }

        /**
         * @param $tag
         */
        public function addTag($tag)
        {
            var_dump($this->package_tags);
            array_push($this->package_tags, $tag);
            $this->tagRegen();
            var_dump($this->package_tags);
        }

        /**
         *
         */
        private function tagRegen()
        {
            $t = "";
            foreach ($this->package_tags as $key => $value) {
                $t .= ($t == "" ? "" : ",") . $value;
            }
            $this->package_tag = $t;
        }

        /**
         * @return package_conf
         */
        public function WithDefault()
        {
            $bas = clone $this;
            $def = null;
            $userDir = FS::getUserHomeDir();
            if (file_exists($userDir . "/.dna/default_conf.json")) {
                $def = new PkConf(FS::openJson($userDir . "/.dna/default_conf.json"));
            }
            if ($def == null) return $bas;
            else {
                if ($bas->package_name === "" && $def->package_name !== "") $bas->package_name = $def->package_name;
                if ($bas->package_version === "" && $def->package_version !== "") $bas->package_version = $def->package_version;
                if ($bas->package_uid === "" && $def->package_uid !== "") $bas->package_uid = $def->package_uid;
                if ($bas->package_author_group === "" && $def->package_author_group !== "") $bas->package_author_group = $def->package_author_group;
                if ($bas->package_copyright === "" && $def->package_copyright !== "") $bas->package_copyright = $def->package_copyright;
                if ($bas->package_licenseUrl === "" && $def->package_licenseUrl !== "") $bas->package_licenseUrl = $def->package_licenseUrl;
                if ($bas->package_require_license_acceptance === "" && $def->package_require_license_acceptance !== "") $bas->package_require_license_acceptance = $def->package_require_license_acceptance;
                if ($bas->package_websiteUrl === "" && $def->package_websiteUrl !== "") $bas->package_websiteUrl = $def->package_websiteUrl;
                if ($bas->package_docUrl === "" && $def->package_docUrl !== "") $bas->package_docUrl = $def->package_docUrl;
                if ($bas->package_release_note === "" && $def->package_release_note !== "") $bas->package_release_note = $def->package_release_note;
                if ($bas->package_description === "" && $def->package_description !== "") $bas->package_description = $def->package_description;
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

        /**
         * @param bool $all
         * @return string
         */
        public function renderDisplay($all = false)
        {
            /*
                Package:
                  Name: package_name      Version: package_version
                  Uid: package_name_pakege_version@pakege_author_group.author_nic
                  Group: package_author_group

                  Release note: package_release_note
                  Release data: package_release_date
                  Description:
                     package_description

                  Tags: package_tag

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

                  dependencies:
                     N) Name: package_name, Version: package_version, Uid: package_name_pakege_version@pakege_author_group.author_nic
             */
            $Des = "";
            foreach (explode('\n', $this->package_description) as $i => $v) $Des .= "      " . $v . "\n";
            $Owners = "";
            foreach ($this->package_owners as $i => $v)
                $Owners .= "      " . $i . ")\t" . "Name: " . $v->owner_name . ", Surname: " . $v->owner_surname . ", Nic : " . $v->owner_nic . ", Link: " . $v->owner_link . ", Other: " . $v->owner_other . "\n";
            $Ne = "";
            foreach ($this->package_node_extend as $i => $v)
                $Ne .= "      " . $i . ")\t" . "Type: " . $v->node_type . ", Reference: " . $v->node_ref . "  , STATUS: " .
                    (($v->node_type == "http") ?
                        (NET::URLIsValid($v->node_ref . "/dna-node.json") ? core\success::prepare("OK") : core\error::prepare("NO!")) :
                        (file_exists($v->node_ref . "/dna-node.json") ? core\success::prepare("OK") : core\error::prepare("NO!"))
                    ) .
                    "\n";
            $de = "";
            foreach ($this->package_dependencies as $i => $v)
                $de .= "      " . $i . ")\t" . "Name: " . $v->package_name . ", Version: " . $v->package_version . ", Uid: " . $v->package_uid . "\n";
            if ($all) {
                return "Package:\n" .
                    "   Name: " . $this->package_name . ",          Version: " . $this->package_version . "\n" .
                    "   Uid: " . ($this->generateUID() === false ? "the Uid could not be generated" : $this->generateUID()) . "\n" .
                    "   Group: " . $this->package_author_group . "\n\n" .
                    "   Release note: " . $this->package_release_note . "\n" .
                    "   Release date: " . $this->package_release_date . "\n" .
                    "   Description:\n" . $Des . "\n" .
                    "   Tags: " . $this->package_tag . "\n" .
                    "   Icon: " . $this->package_iconUrl . "\n\n" .
                    "   Author:\n" .
                    '      Name: ' . $this->package_author->author_name . '      Surname: ' . $this->package_author->author_surname . "\n" .
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
                    "   dependencies: \n" . $de . "\n";
            }
            return "";
        }

        /**
         * @return bool|string
         */
        public function generateUID()
        {
            if ($this->package_name !== "") {
                if ($this->package_version !== "") {
                    if ($this->package_author_group !== "") {
                        if (isset($this->package_author->author_nic)) {
                            return "{$this->package_name}_$this->package_version@$this->package_author_group." . $this->package_author->author_nic;
                        }
                    }
                }
            }
            return false;
        }

        public function renderingFilter()
        {
            /*
             package_name package_version package_uid package_author_group package_copyright package_licenseUrl
                  package_require_license_acceptance package_websiteUrl package_docUrl package_author package_release_note package_description
                  package_iconUrl package_tag
             */
            $out = "";
            if ($this->package_name !== "") $out .= (($out === "") ? "" : ", ") . "package_name: " . $this->package_name;
            if ($this->package_version !== "") $out .= (($out === "") ? "" : ", ") . "package_version: " . $this->package_version;
            if ($this->package_uid !== "") $out .= (($out === "") ? "" : ", ") . "package_uid: " . $this->package_uid;
            if ($this->package_author_group !== "") $out .= (($out === "") ? "" : ", ") . "package_author_group: " . $this->package_author_group;
            if ($this->package_copyright !== "") $out .= (($out === "") ? "" : ", ") . "package_copyright: " . $this->package_copyright;
            if ($this->package_licenseUrl !== "") $out .= (($out === "") ? "" : ", ") . "package_licenseUrl: " . $this->package_licenseUrl;
            if ($this->package_require_license_acceptance !== false) $out .= (($out === "") ? "" : ", ") . "package_require_license_acceptance: " . $this->package_require_license_acceptance;
            if ($this->package_websiteUrl !== "") $out .= (($out === "") ? "" : ", ") . "package_websiteUrl: " . $this->package_websiteUrl;
            if ($this->package_docUrl !== "") $out .= (($out === "") ? "" : ", ") . "package_docUrl: " . $this->package_docUrl;

            if ($this->package_author->author_name !== "") $out .= (($out === "") ? "" : ", ") . "package_author->author_name: " . $this->package_author->author_name;
            if ($this->package_author->author_surname !== "") $out .= (($out === "") ? "" : ", ") . "package_author->author_surname: " . $this->package_author->author_surname;
            if ($this->package_author->author_nic !== "") $out .= (($out === "") ? "" : ", ") . "package_author->author_nic: " . $this->package_author->author_nic;
            if ($this->package_author->author_link !== "") $out .= (($out === "") ? "" : ", ") . "package_author->author_link: " . $this->package_author->author_link;
            if ($this->package_author->author_other !== "") $out .= (($out === "") ? "" : ", ") . "package_author->author_other " . $this->package_author->author_other;

            if ($this->package_release_note !== "") $out .= (($out === "") ? "" : ", ") . "package_release_note: " . $this->package_release_note;
            if ($this->package_description !== "") $out .= (($out === "") ? "" : ", ") . "package_description: " . $this->package_description;
            if ($this->package_iconUrl !== "") $out .= (($out === "") ? "" : ", ") . "package_iconUrl: " . $this->package_iconUrl;
            $this->tagRegen();
            if ($this->package_tag !== "") $out .= (($out === "") ? "" : ", ") . "package_tag: " . $this->package_tag;

            return $out;
        }

        public function renderingSearch()
        {
            /*
                Search index : 1)
                    Name: XX          Version: XX         By: XX@XX         Author: XX XX
                    UID: XX_XX@XX.XX      Copyright: XX
                    Licence: require acceptance: YES|NO,  link: XX
                    Tags: XX
                    Description: XX
                    release_note : XX
             */

            return  "\tName: ".$this->package_name."          Version: ".$this->package_version."          By: ".$this->package_author_group."@".$this->package_author->author_nic ."         Author: ".$this->package_author->author_name ." ".$this->package_author->author_surname ."\n".
                    "\tUID: ".$this->package_uid."      Copyright: ".$this->package_copyright."\n".
                    "\tLicence: require acceptance: ".($this->package_require_license_acceptance?"Yes":"No").",  link: ".$this->package_licenseUrl."\n".
                    "\tTags: ".$this->package_tag."\n".
                    "\tDescription: ".$this->package_description."\n".
                    "\tRelease_note: ".$this->package_release_note;
        }

        /**
         * @param package_conf $PkTo return true if this package can is PkTo
         * @return bool
         */
        public function match($PkTo)
        {
            return package_conf::compare($this, $PkTo);
        }

        /**
         * @param package_conf $PkFrom
         * @param package_conf $PkTo
         * @return bool
         */
        public static function compare($PkFrom, $PkTo)
        {

            if (isset($PkFrom->package_name)) if ($PkFrom->package_name !== "" && $PkTo->package_name != "") {
                if ($PkFrom->package_name !== $PkTo->package_name) return false;
            }
            if (isset($PkFrom->package_version)) if ($PkFrom->package_version !== "" && $PkTo->package_version != "") {
                if ($PkFrom->package_version !== $PkTo->package_version) return false;
            }
            if (isset($PkFrom->package_uid)) if ($PkFrom->package_uid !== "" && $PkTo->package_uid != "") {
                if ($PkFrom->package_uid !== $PkTo->package_uid) return false;
            }
            if (isset($PkFrom->package_author_group)) if ($PkFrom->package_author_group !== "" && $PkTo->package_author_group != "") {
                if ($PkFrom->package_author_group !== $PkTo->package_author_group) return false;
            }
            if (isset($PkFrom->package_release_note)) if ($PkFrom->package_release_note !== "" && $PkTo->package_release_note != "") {
                if ($PkFrom->package_release_note !== $PkTo->package_release_note) return false;
            }
            if (isset($PkFrom->package_release_date)) if ($PkFrom->package_release_date !== "" && $PkTo->package_release_date != "") {
                if ($PkFrom->package_release_date !== $PkTo->package_release_date) return false;
            }
            if (isset($PkFrom->package_description)) if ($PkFrom->package_description !== "" && $PkTo->package_description != "") {
                if ($PkFrom->package_description !== $PkTo->package_description) return false;
            }

            if (isset($PkFrom->package_tag)) if ($PkFrom->package_tag !== "" && $PkTo->package_tag != "") {
                foreach ($PkFrom->package_tags as $i => $v) {
                    if (!in_array($v, $PkTo->package_tags, true)) {
                        var_dump($PkTo->package_tags);
                        var_dump($PkFrom->package_tags);
                        return false;

                    }
                }
            }

            if (isset($PkFrom->package_iconUrl)) if ($PkFrom->package_iconUrl !== "" && $PkTo->package_iconUrl != "") {
                if ($PkFrom->package_iconUrl !== $PkTo->package_iconUrl) return false;
            }

            if (isset($PkFrom->package_author)) {
                if (isset($PkFrom->package_author->author_name)) if ($PkFrom->package_author->author_name !== "" && $PkTo->package_author->author_name != "") {
                    if ($PkFrom->package_author->author_name !== $PkTo->package_author->author_name) return false;
                }
                if (isset($PkFrom->package_author->author_surname)) if ($PkFrom->package_author->author_surname !== "" && $PkTo->package_author->author_surname != "") {
                    if ($PkFrom->package_author->author_surname !== $PkTo->package_author->author_surname) return false;
                }
                if (isset($PkFrom->package_author->author_nic)) if ($PkFrom->package_author->author_nic !== "" && $PkTo->package_author->author_nic != "") {
                    if ($PkFrom->package_author->author_nic !== $PkTo->package_author->author_nic) return false;
                }
                if (isset($PkFrom->package_author->author_link)) if ($PkFrom->package_author->author_link !== "" && $PkTo->package_author->author_link != "") {
                    if ($PkFrom->package_author->author_link !== $PkTo->package_author->author_link) return false;
                }
                if (isset($PkFrom->package_author->author_other)) if ($PkFrom->package_author->author_other !== "" && $PkTo->package_author->author_other != "") {
                    if ($PkFrom->package_author->author_other !== $PkTo->package_author->author_other) return false;
                }
            }
            if (isset($PkFrom->package_copyright)) if ($PkFrom->package_copyright !== "" && $PkTo->package_copyright != "") {
                if ($PkFrom->package_copyright !== $PkTo->package_copyright) return false;
            }
            if (isset($PkFrom->package_licenseUrl)) if ($PkFrom->package_licenseUrl !== "" && $PkTo->package_licenseUrl != "") {
                if ($PkFrom->package_licenseUrl !== $PkTo->package_licenseUrl) return false;
            }
            if (isset($PkFrom->package_require_license_acceptance)) if ($PkFrom->package_require_license_acceptance !== "" && $PkTo->package_require_license_acceptance != "") {
                if ($PkFrom->package_require_license_acceptance !== $PkTo->package_require_license_acceptance) return false;
            }
            if (isset($PkFrom->package_websiteUrl)) if ($PkFrom->package_websiteUrl !== "" && $PkTo->package_websiteUrl != "") {
                if ($PkFrom->package_websiteUrl !== $PkTo->package_websiteUrl) return false;
            }
            if (isset($PkFrom->package_docUrl)) if ($PkFrom->package_docUrl !== "" && $PkTo->package_docUrl != "") {
                if ($PkFrom->package_docUrl !== $PkTo->package_docUrl) return false;
            }

            return true;
        }
    }
}