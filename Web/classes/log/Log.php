<?php

/**
 * Created by PhpStorm.
 * User: erik
 * Date: 9-4-17
 * Time: 17:16
 */
class Log
{

    const HEADER = 1;
    const NOTICE = 2;
    const MEDIUM_IMPORTANT = 3;
    const HIGH_IMPORTANT = 4;

    private $messages = [];

    public function write($msg, $type) {
        $this->messages[] = [ $msg, $type ];
    }

    public function printOut() {
        $output = [];
        foreach($this->messages as $msg) {
            $output[] = $this->printLine($msg);
        }
        echo implode('', $output);
    }

    private function printLine($msg) {
        return '<div style="' . $this->getStyle($msg[1]) . '">' . $msg[0] . '</div>';
    }

    private function getStyle($style) {
        switch($style) {
            case self::HEADER: {
                return "font-size: 20px;";
            }
            case self::NOTICE: {
                return "font-size: 14px;";
            }
            case self::MEDIUM_IMPORTANT: {
                return "color: darkgoldenrod;";
            }
            case self::HIGH_IMPORTANT: {
                return "color: red;";
            }
            default: {
                return "";
            }
        }
    }

}
