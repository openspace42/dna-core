<?php



namespace dna\core {

    require_once __DIR__ . "/fileSystem.php";
    require_once __DIR__ . "/package_conf.php";
    require_once __DIR__ . "/dna_conf.php";

    use dna\core as core;

    class dna
    {
        private $run_path = "";

        //region construct

        public function __construct($args, $dir)
        {
            $this->run_path = $dir;
            $args = array_slice($args, 1);
            $buffer_args = array();
            $op = "";
            /*
                install build run compile conf help
            */
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
            if ($op == "i") {
                $this->install($buffer_args);
            } else if ($op == "b") {
                $this->build($buffer_args);
            } else if ($op == "r") {
                $this->run($buffer_args);
            } else if ($op == "c") {
                $this->compile($buffer_args);
            } else if ($op == "co") {
                new dna_conf($buffer_args,$this->run_path );
            } else if ($op == "h") {
                $this->help($buffer_args);
            } else {
                new core\error("no op select");
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
                        new core\error("dna install don't have [\"" . $arg . "\"] argument, please user dna install -h for see the installation help", true);
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



        private function install_worker()
        {

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
                        new core\error("dna build don't have [\"" . $arg . "\"] argument, please user dna build -h for see the building help", true);
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

         }

        private function run($args)
        {
            new core\message("run: ");
            var_dump($args);
        }

        //endregion

        //region build


        private function compile($args)
        {
            new core\message("compile: ");
            var_dump($args);
        }





        //endregion

        private function help($args)
        {
            new core\message("help: ");
            var_dump($args);
        }

        //region other function


        //endregion
    }
}