<?php

namespace App;

class Page
{
    private string $url;
    private array $gets;

    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setGets(array $gets) {
        $this->gets = $gets;
    }

    public function getGets(){
        $ret = '';
        if (!empty($this->gets)){
            foreach($this->gets as $get => $val) {
                $ret .= (strlen($ret) > 0) ? '&' : '?';
                $ret .= $get . '=' . $val;
            }
        }
        return $ret;
    }
}
