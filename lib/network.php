<?php

namespace dna\core {

    class network
    {

        public static function URLIsValid($URL)
        {
            $exists = true;
            $file_headers = @get_headers($URL);
            $InvalidHeaders = array('404', '403', '500');
            foreach ($InvalidHeaders as $HeaderVal) {
                if (strstr($file_headers[0], $HeaderVal)) {
                    $exists = false;
                    break;
                }
            }
            return $exists;
        }

        public static function openJson($URL, $ReqType = "GET")
        {
            if ($ReqType === "GET")
                return json_decode(network::GET($URL), true);
            else if ($ReqType === "POST") {

            } else
                return json_decode("{}");
        }

        public static function GET($s)
        {
            return file_get_contents($s);
        }
    }
}