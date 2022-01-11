<?php

namespace Falc;

class Falc
{

    /**
     * @var array
     */
    private $miscNumbers = [
        10 => 'dix',
        11 => 'onze',
        12 => 'douze',
        13 => 'treize',
        14 => 'quatorze',
        15 => 'quinze',
        16 => 'seize',
        20 => 'vingt',
        30 => 'trente',
        40 => 'quarante',
        50 => 'cinquante',
        60 => 'soixante',
        100 => 'cent'
    ];

    /**
     * @var array
     */
    private $digits = [
        1 => 'un',
        2 => 'deux',
        3 => 'trois',
        4 => 'quatre',
        5 => 'cinq',
        6 => 'six',
        7 => 'sept',
        8 => 'huit',
        9 => 'neuf'
    ];

    /**
     * @var array
     */
    private $exponent = [
        0 => '',
        3 => 'mille',
        6 => 'million',
        6 => 'millions',
        9 => 'milliard',
        9 => 'milliards',
        12 => 'billion', // was 'trillion',
        15 => 'quadrillion',
        18 => 'quintillion',
        21 => 'sextillion',
        24 => 'septillion',
        27 => 'octillion',
        30 => 'nonillion',
        33 => 'decillion',
        36 => 'undecillion',
        39 => 'duodecillion',
        42 => 'tredecillion',
        45 => 'quattuordecillion',
        48 => 'quindecillion',
        51 => 'sexdecillion',
        54 => 'septendecillion',
        57 => 'octodecillion',
        60 => 'novemdecillion',
        63 => 'vigintillion',
        66 => 'unvigintillion',
        69 => 'duovigintillion',
        72 => 'trevigintillion',
        75 => 'quattuorvigintillion',
        78 => 'quinvigintillion',
        81 => 'sexvigintillion',
        84 => 'septenvigintillion',
        87 => 'octovigintillion',
        90 => 'novemvigintillion',
        93 => 'trigintillion',
        96 => 'untrigintillion',
        99 => 'duotrigintillion',
    ];

    /**
     * @var bool
     */
    private $is_span_opened = false;


    /**
     * @var string
     */
    private $and = 'et';

    /**
     * @var string
     */
    private $dash = '-';

    /**
     * @param string $srt
     * @return mixed|string
     */
    public function wordCount(string $srt)
    {
        try{
            $count = str_word_count($srt);
        } catch (\Exception $e){
            return $e->getMessage();
        }

        return $count;
    }

    /**
     * @param string $str
     * @param string|null $type
     * @param string|null $class
     * @return array|string
     */
    public function hyphen(string $str, string $type = null, string $class = null)
    {
        try {
            switch (strtolower($type)) {
                case null:
                    $words = str_word_count($str, 1);
                    $contains = [];
                    foreach ($words as $key => $word) {
                        if (strpos($word, "-")) {
                            echo $word;
                            array_push($contains, $word);
                        }
                    }
                    break;
                case "span":
                    $words = str_word_count($str, 1);
                    $contains = "";
                    $needle = "-";
                    foreach ($words as $key => $word) {
                        if (strpos($word, $needle)) {
                            if (is_null($class)) {
                                $contains .= "<span>" . $word . "<span>" . " ";
                            } else {
                                $contains .= "<span class='$class'>" . $word . "<span>" . " ";
                            }
                        } else {
                            $contains .= $word . " ";
                        }
                    }
                    $contains = mb_substr($contains, 0, -1);
                    break;
                default:
                    return "type non reconnue";
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $contains;
    }

    /**
     * @param string $str
     * @param string|null $breaker
     * @return array|mixed|string
     */
    public function lineBreak(string $str, string $breaker = null, array $list = null)
    {
        try{
            switch (strtolower($breaker)) {
                case null:
                    $contains       = [];
                    $lastPos        = 0;
                    $needle         = ".";
                    $listBreaker    = array();
                    while (($lastPos = strpos($str, $needle, $lastPos)) !== false) {
                        $contains[] = $lastPos;
                        $lastPos = $lastPos + strlen($needle);
                    }

                    break;
                case "br":
                case "rn":
                    $needle     = ".";
                    $lastPos    = 0;
                    $start_pos  = 0;
                    $f_string   = "";

                    while (($lastPos = strpos($str, $needle, $lastPos)) !== false) {
                        $offsetPos = $lastPos - $start_pos;
                        if (substr($str, $lastPos, 3) == "..."){
                            $partString = substr($str, $start_pos, $offsetPos+3);
                            $lastPos    = $lastPos + 3;
                        } else {
                            $partString = substr($str, $start_pos, $offsetPos+1);
                            $lastPos = $lastPos + 1;
                        }
                        $start_pos  =   $lastPos;
                        $f_string   .= trim($partString) . (($breaker == "br")?'<br />':'\r\n');

                    }

                    if ($list){
                        foreach ($list as $key => $el){

                            $listBreaker[]=  $el . (($breaker == "br")?'<br />':'\r\n');
                        }
                        $f_string = str_replace($list, $listBreaker , $f_string);
                    }

                    $contains = $f_string;
                    break;
                default:
                    return "type non reconnue";
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
        return $contains;
    }


    /**
     * @param string $str
     * @param array $list
     * @param string|null $type
     * @param string|null $class
     * @return array|string|mixed|string
     */
    public function specialChar(string $str, array $list, string $type = null, string $class = null)
    {
        try {
            switch (strtolower($type)) {
                case null:
                    $contains = [];
                    foreach ($list as $elem) {
                        $elemFind = [];
                        $lastPos = 0;
                        $needle = $elem;
                        while (($lastPos = strpos($str, $needle, $lastPos)) !== false) {
                            $elemFind[] = $lastPos;
                            $lastPos = $lastPos + strlen($needle);
                        }
                        $contains["$elem"] = $elemFind;
                    }
                    break;
                case "span":
                    $contains = $str;
                    foreach ($list as $elem) {
                        $needle = $elem;
                        if (is_null($class)) {
                            $contains = str_replace($needle, "<spans>$elem</spans>", $contains);
                        }else{
                            $contains = str_replace($needle, "<spans class='$class'>$elem</spans>", $contains);
                        }
                    }
                    break;
                default:
                    return "type non reconnue";
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $contains;
    }

    /**
     * @param $s
     * @return bool
     */
    private function isANumber($s) {
        return in_array($s, $this->miscNumbers) || in_array($s, $this->digits) || in_array($s, $this->exponent);
    }

    /**
     * @param $key
     * @param $a
     * @param $add
     * @return string
     */
    private function getNextString($key, $a, $add) {
        $nextString     =   "";
        if ($key < (count($a) - 1) ) {
            $nextString     =   $a[$key+$add];
            if( $nextString == $this->and ) {
                $nextString =  $this->getNextString($key, $a, $add+1);
            }
        }
        return $nextString;
    }

    /**
     * @param $string
     * @param string $next
     * @return string
     */
    public function numberInLetter(string $string, $next = "", string $class = null) {
        $array_string           =   explode(" ", $string);
        $array_string_after     =   [];
        $this->is_span_opened   =   false;
        $count                  =   count($array_string);
        foreach($array_string as $key => $s) {
            $is_dash_in_s   =   strpos($s, $this->dash);
            if( $is_dash_in_s !== false ) {
                $next_string            =   $this->getNextString($key, $array_string, 1);
                $sub_string             =   str_replace(" ", "-", $this->numberInLetter(str_replace($this->dash, " ", $s),$next_string));
                $array_string_after[]   =   $sub_string;
                continue 1;
            }
            if ( $this->isANumber($s) ) {
                if( !$this->is_span_opened ) {
                    $this->is_span_opened = true;
                    //$array_string_after[] = '<span>' . $s;// . '</span>';
                    if (!is_null($class) && !empty($class)){
                        $array_string_after[] = "<span class='$class'>" . $s;// . '</span>';
                    }else{
                        $array_string_after[] = "<span>" . $s;// . '</span>';
                    }
                } else {
                    $array_string_after[] = $s;// . '</span>';
                }
            } else {
                if( $this->is_span_opened ) {
                    if( $s == $this->and && $this->isANumber($array_string[$key+1]) ) {
                        $array_string_after[] = $s;
                    } else {
                        $array_string_after[] = '</span>' . $s;
                        $this->is_span_opened = false;
                    }
                } else {
                    $array_string_after[] = $s;
                }
            }

        }
        if( $this->is_span_opened && (!empty($next) && !$this->isANumber($next) || empty($next)) ) {
            $array_string_after[$count-1] = $array_string_after[$count-1] . '</span>';
        }
        return join(" ", $array_string_after);
    }

    /**
     * @param string $string
     * @param string|null $type
     * @param string|null $class
     * @return mixed|string
     */
    public function complexTitle(string $string, string $type = null, string $class = null) {
        preg_match_all('/[a-zA-Z0-9][.][a-zA-Z0-9][.][a-zA-Z0-9]/', $string, $matches);

        try{

            switch (strtolower($type)) {
                case null:
                    return $matches;
                    break;
                case "span":
                    if (is_null($class)) {
                        foreach ($matches[0] as $key => $match){
                            $string = str_replace($match, "<span>" . $match . "</span>", $string);
                        }
                    }else{
                        foreach ($matches[0] as $key => $match){
                            $string = str_replace($match, "<span class='$class'>" . $match . "</span>", $string);
                        }
                    }

                    break;
                default:
                    return "type non reconnue";
            }

        }catch (\Exception $e){
            return $e->getMessage();
        }

        return $string;

    }
}