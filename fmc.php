#!/usr/bin/php
<?php

// File Modification Checker

class fmc {
    public $date;
    public $project_url = "test";
    public $current_filename = "@current.txt";
    public $modified_filename = "@modified.txt";

    public $yellow = "\033[1;33m";
    public $red = "\033[1;31m";
    public $color_reset = "\033[0m";

    private function set_color($string, $error = 0) {
        if ($error == 1) $color = $this->red;
        else $color = $this->yellow;
        return $color.$string.$this->color_reset;
    }

    public function init() {
        $current_fullpath = "{$this->project_url}/{$this->current_filename}";
        $output = file_put_contents($current_fullpath, $this->date);
        if ($output !== false) return $this->set_color("\nФайл записан | {$current_fullpath}\n\n");
        else return $this->set_color("\nНе удалось записать файл | {$current_fullpath}\n\n", 1);
    }

    public function check() {
        
    }

    public function clear() {
        
    }
}

$fmc = new fmc();
$fmc->date = time();

if ( !isset($argv[1]) ) exit ("Не указаны парамерты\n");

switch ($argv[1]) {
    case "init":
        echo $fmc->init();
        break;
    case "clear":
        echo $fmc->clear();
        break;
    default:
        echo "Неправильный параметр, доступно init\n";
}

//$cv->date = strtotime("now");
//var_dump($argv);

/*
  Создаём файл "@current.txt", в котором хранится запись даты и времени на момент запуска (init)
  Читаем файл "@current.txt", получаем из него дату и ищем все файлы, созданные после этой даты, пихаем пути и имена файлов в массив (check)
  Записываем массив в файл "@modified.txt"

*/


?>
