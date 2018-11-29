<?php

namespace dna\core {
    require_once __DIR__ . "/dna_messages.php";

    use dna\core as core;

    class fileSystem
    {
        public static function getChar($f)
        {
            return fgetc($f);
        }

        public static function writeAllPath($p, $s, $pr = false)
        {
            $f = fileSystem::open($p, false, true, $pr);
            fileSystem::write($f, $s);
            fileSystem::close($f);
        }

        public static function open($s, $r = true, $w = false, $p = false)
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

        public static function write($f, $s)
        {
            fwrite($f, $s);
        }

        public static function close($f)
        {
            return fclose($f);
        }

        public static function toAbsolutePath($p)
        {
            return realpath($p);
        }

        public static function openJson($p)
        {
            return json_decode(fileSystem::getAllPath($p), true);
        }

        public static function getAllPath($p)
        {
            $f = fileSystem::open($p);
            $ret = fileSystem::getAll($f);
            fileSystem::close($f);
            return $ret;
        }

        public static function getAll($f)
        {
            $ret = "";
            while (!feof($f)) {
                $ret .= fileSystem::getLine($f);
            }
            return $ret;
        }

        public static function getLine($f)
        {
            return fgets($f);
        }

        public static function JsonStringRemoveEmpty($j)
        {
            return json_encode(fileSystem::JsonRemoveEmpty(json_decode($j, true)), JSON_PRETTY_PRINT);
        }

        public static function JsonRemoveEmpty($j)
        {
            foreach ($j as $k => &$v) {
                if (is_array($v)) {
                    $v = fileSystem::JsonRemoveEmpty($v);
                    if (!sizeof($v)) {
                        unset($j[$k]);
                    }
                } elseif (!strlen($v)) {
                    unset($j[$k]);
                }
            }
            return $j;
        }

        public static function getUserHomeDir()
        {
            $home = getenv('HOME');
            if (!empty($_SERVER['HOMEDRIVE']) && !empty($_SERVER['HOMEPATH'])) {
                $home = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
            }
            $ret = (empty($home) ? NULL : $home);
            return $ret;
        }

        public static function IO_get($s, $n = false)
        {
            if (PHP_OS == 'WINNT') {
                new core\info($s, $n);
                return stream_get_line(STDIN, 1024, PHP_EOL);
            } else {
                new core\info($s, $n);
                return readline();
            }
        }

        public static function deleteFolder($dirname)
        {
            array_map('unlink', glob("$dirname/*.*"));
            rmdir($dirname);
        }

        public static function CopyFile($To, $From)
        {
            file_put_contents($To, fopen($From, 'r'));
        }

        public static function generateRandomString($length = 10)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

    }

    use ZipArchive;

    class Zip
    {
        public static function UnZip($file, $to)
        {
            $zip = new ZipArchive;
            $res = $zip->open($file);
            if ($res === TRUE) {
                $zip->extractTo($to);
                $zip->close();
                return true;
            } else {
                return false;
            }
        }
    }
}