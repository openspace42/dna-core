<?php


namespace dna\core {

    /**
     * Class error. display a error message
     * @package dna\core
     */
    class error
    {
        /**
         * error constructor. display a error message
         * @param string $s is a error message to display
         * @param bool $e default [ TRUE ] if true die
         * @param bool $n if true add a new line
         */
        public function __construct($s, $e = true,$n=true)
        {
            $s = "\033[38;2;255;179;186m[ERROR]: " . $s . "\033[39m";
            if ($e) {
                new message($s,$n);
                die();
            } else {
                new message($s,$n);
            }

        }
    }

    /**
     * Class message. display a message
     * @package dna\core
     */
    class message
    {
        /**
         * message constructor. display a message
         * @param string $s is a message to display
         * @param bool $n if true add a new line
         */
        public function __construct($s, $n = true)
        {
            $s = "" . $s . ($n ? "\n" : "");
            echo $s;
        }
    }

    /**
     * Class success. display a success message
     * @package dna\core
     */
    class success
    {
        /**
         * success constructor. display a success message
         * @param string $s is a success message to display
         * @param bool $n if true add a new line
         */
        public function __construct($s,$n = true)
        {
            $s = "\033[38;2;186;255;201m" . $s . "\033[39m\n";
            new message($s,$n);
        }

    }

    /**
     * Class warning. display a warning message
     * @package dna\core
     */
    class warning
    {
        /**
         * warning constructor. display a warning message
         * @param string $s is a warning message to display
         * @param bool $n if true add a new line
         */
        public function __construct($s,$n = true)
        {
            $s = "\033[38;2;255;255;186m" . $s . "\033[39m\n";
            new message($s,$n);
        }

    }
    /**
     * Class info. display a info message
     * @package dna\core
     */
    class info
    {
        /**
         * info constructor. display a info message
         * @param string $s is a warning info to display
         * @param bool $n if true add a new line
         */
        public function __construct($s, $n = true)
        {
            $s = "\033[38;2;186;225;255m" . $s . "\033[39m";
            new message($s, $n);
        }
    }

}