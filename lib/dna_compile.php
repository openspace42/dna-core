<?php

namespace dna\core {

    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/dna_messages.php";

    use dna\core\fileSystem as FS;
    use dna\core\package_conf as Pkconf;
    use dna\core as core;

    class dna_compile
    {
        private $run_path = "";
        private $includedFilse = array();
        public $code;
        public function __construct($dit, $baseNamespace = "")
        {
            $this->run_path = $dit;
            if (!file_exists($this->run_path . "/conf.json"))
                new core\error("Unable tu open " . $this->run_path . "/conf.json");
            $Pkconf = new Pkconf(FS::openJson($this->run_path . "/conf.json"));
            if ($baseNamespace === "") $baseNamespace = $Pkconf->package_name;
            $FE = $this->FileEngine($this->run_path . "/program", $baseNamespace, false);
            if ($FE === false) {
                $FE = $this->FileEngine($this->run_path . "/program.php", $baseNamespace, false);
            }
            if ($FE === false) {/* ERROR */
            }
            $this->code=$FE;
            /*
            $CodeBase = "";
            if (!file_exists($this->run_path . "/program")) {
                if (!file_exists($this->run_path . "/program.php")) {
                    new core\error($Pkconf->generateUID() . " don't have a main ( program | program.php )");
                } else {
                    $CodeBase = FS::getAllPath($this->run_path . "/program.php");
                }
            } else {
                $CodeBase = FS::getAllPath($this->run_path . "/program");
            }
            $CodeBase = $this->removeCompileIgnore($CodeBase);

            $detectInc=$this->DetectInclude($CodeBase, $this->run_path . "/program", $baseNamespace);
            var_dump($detectInc);
            $CodeBase=$detectInc[1]."".$detectInc[0];
            var_dump($CodeBase);
            //$this->DetectInclude($CodeBase, $this->run_path . "/program", $baseNamespace);

            //echo $CodeBase;
*/
        }

        private function FileEngine($filename, $baseNamespace, $noNFS = false)
        {
            echo "FILEENGINECALL" . $filename . "\n";
            if (!file_exists($filename)) {
                return false;
            }
            $CodeBase = FS::getAllPath($filename);
            if (substr($CodeBase, 0, 5) === "<?php") $CodeBase = substr($CodeBase, 5);
            $CodeBase = $this->removeCompileIgnore($CodeBase);
            $CodeBase = $this->fixNameSpace($filename, $CodeBase, $baseNamespace, $noNFS);
            $CodeBase = $this->fixUseNameSpace($filename, $CodeBase, $baseNamespace, $noNFS);
            $CodeBase = $this->DetectInclude($CodeBase, $filename, $baseNamespace);
            return $CodeBase;
        }

        private function removeCompileIgnore($code)
        {
            $PosStart = strpos($code, "dna.ignore.compile.start");
            $found = false;
            if ($PosStart !== false)
                while ($PosStart != 3 and !$found) {
                    if (substr($code, $PosStart - 3, 3) === "/**") $found = true;
                    $PosStart = $PosStart - 1;
                }

            if ($found === false and $PosStart !== false)
                new core\error("the tag dna.ignore.compile.start need start with /** and end with */ es: '/** dna.ignore.compile.start */'");

            $PosEnd = strpos($code, "dna.ignore.compile.end", $PosStart);
            $found2 = false;
            if ($PosEnd !== false)
                while ($PosEnd != strlen($code) - 2 and !$found2)
                    if (substr($code, $PosEnd, 2) == "*/") $found2 = true;
                    else $PosEnd = $PosEnd + 1;


            if ($found2 === false and $PosEnd !== false)
                new core\error("the tag dna.ignore.compile.end need start with /** and end with */ es: '/** dna.ignore.compile.start */'");

            if ($PosStart === false && $PosEnd !== false)
                new core\error("the tag dna.ignore.compile.start need dna.ignore.compile.end");

            if ($PosStart !== false && $PosEnd === false) {

                new core\error("the tag dna.ignore.compile.end need dna.ignore.compile.start");
            }


            $PosStart = strpos($code, "dna.ignore.compile.start");
            if ($PosStart !== false) {
                $code = substr($code, 0, $PosStart - 4) .
                    substr($code, $PosEnd + 2);
                $code = $this->removeCompileIgnore($code);
            }
            return $code;
        }

        public function fixNameSpace($fileSource, $code, $namespaceAppend, $noNFS)
        {
            $someFound = false;
            $fpos = 0;
            $PosStart = true;
            while ($PosStart !== false) {

                //echo $namespaceAppend . "------\n";
                $PosStart = strpos($code, "namespace", $fpos);
                $PosStartT = $PosStart;
                $found = false;
                if ($PosStart !== false)
                    while ($PosStart != 2 and !$found) {
                        if (substr($code, $PosStart - 2, 2) === "//") $found = true;
                        if (substr($code, $PosStart - 1, 1) === "\n") $PosStart = 3;
                        $PosStart = $PosStart - 1;
                    }
                $PosStart = $PosStartT;
                if ($found === false) {
                    if ($PosStart !== false)
                        while ($PosStart != 2 and !$found) {
                            if (substr($code, $PosStart - 2, 2) === "/*") $found = true;
                            if (substr($code, $PosStart - 2, 2) === "*/") $PosStart = 3;
                            $PosStart = $PosStart - 1;
                        }
                    $PosStart = $PosStartT;
                    if ($found === true) {

                        $found = false;
                        if ($PosStart !== false)
                            while ($PosStart !== strlen($code) - 2 and !$found) {
                                if (substr($code, $PosStart, 2) === "*/") $found = true;
                                $PosStart = $PosStart + 1;
                            }
                        if ($found === true) {
                            $PosStart = false;
                        } else {
                            $PosStart = $PosStartT;
                        }
                    }
                } else {
                    $PosStart = false;
                }
                if ($PosStart !== false) {
                    $exit = false;
                    $needBkAppend = false;
                    $fpos = false;
                    $PosStart += 9;
                    while (substr($code, $PosStart, 1) !== "{" && $PosStart < strlen($code) - 1 && !$exit) {
                        //echo substr($code, $PosStart, 1) . "---";
                        if (substr($code, $PosStart, 1) === ";") {
                            $prev = substr($code, 0, $PosStart - 2);
                            $preLines = count(explode("\n", $prev));
                            new core\message(core\error::prepare("Error in " . $fileSource . " on line " . $preLines . "\n\n" . $this->GetLinesOfError($code, $PosStart) . core\error::prepare("\nIn dna you can't declare a namespace without context ex GOOD: namespace ... { ... } , BAD  namespace ... ;")));
                            die();
                        } else if (substr($code, $PosStart, 1) === " " or substr($code, $PosStart, 1) === "\n") {
                            if (!$needBkAppend) $fpos = $PosStart;
                        } else {
                            $needBkAppend = true;

                        }
                        $PosStart += 1;
                    }
                    if (!$noNFS)
                        $code = substr($code, 0, $fpos + 1) . $namespaceAppend . ($needBkAppend ? "\\" : "") .
                            substr($code, $fpos + 1);
                    $someFound = true;

                }
                $StrartFrom = $fpos + 1;
            }
            if (!$someFound) return "\nnamespace " . (!$noNFS ? $namespaceAppend : "") . " {\n" . $code . "\n}\n";
            else
                return $code;

        }

        //$includeMaP=array();

        private function GetLinesOfError($code, $pos)
        {
            $ret = "";
            $prev = substr($code, 0, $pos - 2);
            $preLines = count(explode("\n", $prev));
            //echo $preLines."-----\n".$prev."-------\n";
            if ($preLines < 4) $minLine = $preLines;
            else $minLine = $preLines - 4;
            $TotLine = count(explode("\n", $code));
            $maxLine = 0;
            if ($TotLine - $minLine < 3) $maxLine = $TotLine;
            else $maxLine = $preLines + 3;
            $AllLines = explode("\n", $code);
            for ($i = $minLine; $i < $maxLine; $i++) {
                if ($i == $preLines - 1)
                    $ret .= core\error::prepare($i . "\t───▷    " . $AllLines[$i]);
                else
                    $ret .= core\success::prepare($i . "\t\t" . $AllLines[$i]);
                $ret .= "\n";
            }
            return $ret;

        }

        public function fixUseNameSpace($fileSource, $code, $namespaceAppend, $noNFS)
        {
            $someFound = false;
            $fpos = 0;
            $PosStart = true;
            while ($PosStart !== false) {

                //echo $namespaceAppend . "------\n";
                $PosStart = strpos($code, "use ", $fpos);
                $PosStartT = $PosStart;
                $found = false;
                if ($PosStart !== false)
                    while ($PosStart != 2 and !$found) {
                        if (substr($code, $PosStart - 2, 2) === "//") $found = true;
                        if (substr($code, $PosStart - 1, 1) === "\n") $PosStart = 3;
                        $PosStart = $PosStart - 1;
                    }
                $PosStart = $PosStartT;
                if ($found === false) {
                    if ($PosStart !== false)
                        while ($PosStart != 2 and !$found) {
                            if (substr($code, $PosStart - 2, 2) === "/*") $found = true;
                            if (substr($code, $PosStart - 2, 2) === "*/") $PosStart = 3;
                            $PosStart = $PosStart - 1;
                        }
                    $PosStart = $PosStartT;
                    if ($found === true) {

                        $found = false;
                        if ($PosStart !== false)
                            while ($PosStart !== strlen($code) - 2 and !$found) {
                                if (substr($code, $PosStart, 2) === "*/") $found = true;
                                $PosStart = $PosStart + 1;
                            }
                        if ($found === true) {
                            $PosStart = false;
                        } else {
                            $PosStart = $PosStartT;
                        }
                    }
                } else {
                    $PosStart = false;
                }

                if ($PosStart !== false) {
                    $found2 = false;
                    $found = false;
                    while ($PosStart !== strlen($code) - 2 and !$found and !$found2) {
                        if (substr($code, $PosStart, 1) === "\"") $found = true;
                        if (substr($code, $PosStart, 1) === ";") $found2 = true;
                        $PosStart = $PosStart + 1;
                    }
                    if ($found2) {
                        $PosStart = $PosStartT;
                    }
                    if ($found) {
                        $fpos=$PosStart;
                        $PosStart = false;
                    }
                }

                if ($PosStart !== false) {
                    $exit = false;
                    $needBkAppend = false;
                    $fpos = false;
                    $PosStart += 3;
                    while (substr($code, $PosStart, 1) !== ";" && $PosStart < strlen($code) - 1 && !$exit) {
                        //echo substr($code, $PosStart, 1) . "---";
                        if (substr($code, $PosStart, 1) === " " or substr($code, $PosStart, 1) === "\n") {
                            if (!$needBkAppend) $fpos = $PosStart;
                        } else {
                            $needBkAppend = true;

                        }
                        $PosStart += 1;
                    }
                    if (!$noNFS)
                        $code = substr($code, 0, $fpos + 1) . $namespaceAppend . ($needBkAppend ? "\\" : "") .
                            substr($code, $fpos + 1);
                    $someFound = true;

                }
                $StrartFrom = $fpos + 1;
            }

            return $code;

        }

        private function DetectInclude($code, $fileSource, $baseNamespace)
        {
            //echo "\n\n---\n".$code."\n----\n\n";
            $PosStart = strpos($code, "dna_include_once(");
            $PosStartT = $PosStart;
            $PosStartForDEl = $PosStart;
            $found = false;
            if ($PosStart !== false)
                while ($PosStart != 2 and !$found) {
                    if (substr($code, $PosStart - 2, 2) === "//") $found = true;
                    if (substr($code, $PosStart - 1, 1) === "\n") $PosStart = 3;
                    $PosStart = $PosStart - 1;
                }
            $PosStart = $PosStartT;
            if ($found === false) {
                if ($PosStart !== false)
                    while ($PosStart != 2 and !$found) {
                        if (substr($code, $PosStart - 2, 2) === "/*") $found = true;
                        if (substr($code, $PosStart - 2, 2) === "*/") $PosStart = 3;
                        $PosStart = $PosStart - 1;
                    }
                $PosStart = $PosStartT;
                if ($found === true) {

                    $found = false;
                    if ($PosStart !== false)
                        while ($PosStart !== strlen($code) - 2 and !$found) {
                            if (substr($code, $PosStart, 2) === "*/") $found = true;
                            $PosStart = $PosStart + 1;
                        }
                    if ($found === true) {
                        $PosStart = false;
                    } else {
                        $PosStart = $PosStartT;
                    }
                }
            } else {
                $PosStart = false;
            }
            $args = array();
            if ($PosStart !== false) {
                $PosStart = $PosStart + 17;

                $argBuffer = "";
                $argIsOpen = false;
                $argOpenChar = "";
                $argCloseCharSkip = false;
                while (substr($code, $PosStart, 1) !== ";" && $PosStart < strlen($code) - 1) {
                    $char = substr($code, $PosStart, 1);
                    // echo $char . "--" . $argBuffer . "--" . $argIsOpen . "--" . $argOpenChar . "--" . $argCloseCharSkip . "\n";
                    if ($char === "\"") {
                        if (!$argCloseCharSkip) {
                            if ($argIsOpen) {
                                if ($argOpenChar === "\"") {
                                    $argIsOpen = false;
                                    $argOpenChar = "";
                                } else {
                                    $argBuffer .= $char;
                                }
                            } else {
                                if ($argBuffer === "") {
                                    $argIsOpen = true;
                                    $argOpenChar = $char;
                                } else {
                                    $argBuffer .= "\\" . $char;
                                    $argCloseCharSkip = false;
                                    //error
                                    echo "EX1";
                                }
                            }
                        } else {
                            if ($argIsOpen) {
                                $argBuffer .= $char;
                                $argCloseCharSkip = false;
                            } else {
                                //error
                                echo "EX2";
                            }
                        }
                    } else if ($char === "'") {
                        if (!$argCloseCharSkip) {
                            if ($argIsOpen) {
                                if ($argOpenChar === "") {
                                    $argIsOpen = false;
                                    $argOpenChar = "";
                                } else {
                                    $argBuffer .= $char;
                                }
                            } else {
                                if ($argBuffer === "") {
                                    $argIsOpen = true;
                                    $argOpenChar = $char;
                                } else {
                                    $argBuffer .= "\\" . $char;
                                    $argCloseCharSkip = false;
                                    echo "EX3";
                                }
                            }
                        } else {
                            if ($argIsOpen) {
                                $argCloseCharSkip = false;
                                $argBuffer .= $char;
                            } else {
                                echo "EX4";
                            }
                        }
                    } else if ($char === "\\") {
                        if (!$argCloseCharSkip) {
                            $argCloseCharSkip = true;
                        } else {
                            $argCloseCharSkip = false;
                            $argBuffer .= $char;
                        }

                    } else if ($char === ",") {
                        if (!$argCloseCharSkip) {
                            if (!$argIsOpen) {
                                array_push($args, $argBuffer);
                                $argBuffer = "";

                            } else {
                                $argBuffer .= $char;
                            }
                        } else {
                            echo "EX5";
                        }

                    } else if ($char === ")") {
                        if (!$argIsOpen) {
                            array_push($args, $argBuffer);
                            $argBuffer = "";

                        } else {
                            $argBuffer .= $char;
                        }

                    } else if ($char === "\n") {
                        if ($argIsOpen)
                            $argBuffer .= $char;

                    } else if ($char === " ") {
                        if ($argIsOpen)
                            $argBuffer .= $char;

                    } else {
                        if ($argCloseCharSkip) {
                            $argBuffer .= "\\" . $char;
                            $argCloseCharSkip = false;
                        } else
                            $argBuffer .= $char;
                    }
                    $PosStart += 1;
                }
                if ($argBuffer !== "")
                    array_push($args, $argBuffer);
            }
            $PosEndForDEl = $PosStart;
            if (count($args) === 2) {

                $code = substr($code, 0, $PosStartForDEl) . ";use " . $baseNamespace . " as " . $args[1] . ";" . substr($code, $PosEndForDEl);
                $ret = $this->dna_include_once($this->run_path . "/" . $args[0], $args[1], $baseNamespace);
                if ($ret === false) {
                    $prev = substr($code, 0, $PosStart - 2);
                    $preLines = count(explode("\n", $prev));
                    new core\message(core\error::prepare("Error in " . $fileSource . " on line " . $preLines . "\n\n" . $this->GetLinesOfError($code, $PosStart) . core\error::prepare("\nI can't fount " . $this->run_path . "/" . $args[0])));

                }


                $ret = $ret . $this->DetectInclude($code, $fileSource, $baseNamespace);
                //echo $ret;
                return $ret;
            } else if (count($args) === 1) {
                $code = substr($code, 0, $PosStartForDEl) . substr($code, $PosEndForDEl);
                $ret = $this->dna_include_once($this->run_path . "/" . $args[0], "", $baseNamespace);
                if ($ret === false) {
                    $prev = substr($code, 0, $PosStart - 2);
                    $preLines = count(explode("\n", $prev));
                    new core\message(core\error::prepare("Error in " . $fileSource . " on line " . $preLines . "\n\n" . $this->GetLinesOfError($code, $PosStart) . core\error::prepare("\nI can't fount " . $this->run_path . "/" . $args[0])));

                }


                $ret = $ret . $this->DetectInclude($code, $fileSource, $baseNamespace);
                //echo $ret;
                return $ret;

            } else if (count($args) === 0) {
                return $code;
            } else {
                $prev = substr($code, 0, $PosStart - 2);
                $preLines = count(explode("\n", $prev));
                new core\message(core\error::prepare("Error in " . $fileSource . " on line " . $preLines . "\n\n" . $this->GetLinesOfError($code, $PosStart) . core\error::prepare("\nThe function dna_include_once declaration is dna_include_once(\"Path/to/file.php\",\"as of use\")")));
            }
        }

        private function dna_include_once($fileName, $useAs, $baseNamespace)
        {
            if (!in_array($fileName, $this->includedFilse)) {
                array_push($this->includedFilse, $fileName);

                return $this->FileEngine($fileName, $baseNamespace);
            }
            return "";
        }
    }
}