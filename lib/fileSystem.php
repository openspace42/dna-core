<?php

namespace dna\core {

    class fileSystem
    {
        public function open($s, $r = true, $w = false, $p = false)
        {
            $m = "";
            if ($r && !$w && !$p) {
                $m = "r";
            }
            if ($r && !$w && $p) {
                $m = "r";
            }
            if ($r && $w && !$p) {
                $m = "r+";
            }
            if ($r && $w && $p) {
                $m = "a+";
            }
            if (!$r && !$w && !$p) {
                return false;
            }
            if (!$r && !$w && $p) {
                return false;
            }
            if (!$r && $w && !$p) {
                $m = "w";
            }
            if (!$r && $w && $p) {
                $m = "a";
            }

            $a = fopen($s, $m) or die("file not found: " . $s);
            if (isset($a)) {
                return $a;
            } else {
                return false;
            }
        }

        public function close($f)
        {
            return fclose($f);
        }

        public function getChar($f)
        {
            return fgetc($f);
        }

        public function getLine($f)
        {
            return fgets($f);
        }

        public function getAll($f)
        {
            $ret = "";
            while (!feof($f)) {
                $ret .= fileSystem::getLine($f);
            }
            return $ret;
        }

        public function getAllPath($p)
        {
            $f = fileSystem::open($p);
            $ret = fileSystem::getAll($f);
            fileSystem::close($f);
            return $ret;
        }

        public function write($f, $s)
        {
            fwrite($f, $s);
        }

        public function writeAllPath($p, $s, $pr = false)
        {
            $f = fileSystem::open($p, false, true, $pr);
            fileSystem::write($f, $s);
            fileSystem::close($f);
        }

        public function toAbsolutePath($p)
        {
            return realpath($p);
        }

        public function opneJson($p)
        {
            return json_decode(fileSystem::getAllPath($p), true);
        }

        public function JsonRemveEmpty($j)
        {
            foreach ($j as $k => &$v) {
                if (is_array($v)) {
                    $v = fileSystem::JsonRemveEmpty($v);
                    if (!sizeof($v)) {
                        unset($j[$k]);
                    }
                } elseif (!strlen($v)) {
                    unset($j[$k]);
                }
            }
            return $j;
        }

        public function JsonStringRemveEmpty($j)
        {
            return json_encode(fileSystem::JsonRemveEmpty(json_decode($j, true)), JSON_PRETTY_PRINT);
        }

        public function getUserHomeDir()
        {
            $home = getenv('HOME');
            if (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
                $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
            }
            $ret = (empty($home) ? NULL : $home);
            return $ret;
        }
    }
}