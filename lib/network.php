<?php

namespace dna\core {

    class network
    {

        public static function openJson($URL, $ReqType = "GET")
        {
            if ($ReqType === "GET") {
                $data = network::GET($URL);
                if ($data === false) {
                    return json_decode("{}");
                }
                return json_decode($data, true);
            } else if ($ReqType === "POST") {
                return json_decode("{}");
            } else
                return json_decode("{}");
        }

        public static function GET($s)
        {
            try {
                if (network::URLIsValid($s))
                    return file_get_contents($s);
                else
                    return false;
            } catch (\Exception $e) {
                return false;
            }
        }

        public static function URLIsValid($URL)
        {
            $exists = true;
            $file_headers = @get_headers($URL);
            if ($file_headers == false) return false;
            $InvalidHeaders = array('404', '403', '500');
            foreach ($InvalidHeaders as $HeaderVal) {
                if (strstr($file_headers[0], $HeaderVal)) {
                    $exists = false;
                    break;
                }
            }
            return $exists;
        }

        public static function Download($To, $From)
        {
            file_put_contents($To, file_get_contents($From));
        }
    }
}