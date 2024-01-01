<?php
    namespace App\Contracts;


    Interface Plugin
    {
        public function getName();
        public function getDescription();
        public function getVersion();
        public function register();
        public function call();
    }
?>
