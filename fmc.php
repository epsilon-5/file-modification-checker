#!/usr/bin/php
<?php

// File Modification Checker

class fmc {
    public $date;
    public $project_url = "test";
    public $current_filename = "@current.txt";
    public $modified_filename = "@modified.txt";
    private $current_fullpath;
    private $modified_fullpath;

    private $yellow = "\033[1;33m";
    private $red = "\033[1;31m";
    private $color_reset = "\033[0m";

    public function __construct() {
        $this->current_fullpath = "{$this->project_url}/{$this->current_filename}";
        $this->modified_fullpath = "{$this->project_url}/{$this->modified_filename}";
    }

    private function scan_files($dir) {
        $result = array();
        $root = scandir($dir);

        foreach ($root as $value) {

            if (
                ($value == ".") ||
                ($value == "..") ||
                ($value == $this->current_filename) ||
                ($value == $this->modified_filename)
               ) continue;

            if ( is_file($filename = "{$dir}/{$value}") ) {
                $result[] = array("filename" => $filename, "modified" => filemtime($filename));
                continue;
            }
            foreach ($this->scan_files("{$dir}/{$value}") as $value) {
                $result[] = $value;
            }
        }
        return $result;
    }

    private function set_color($string, $error = 0) {
        if ($error == 1) $color = $this->red;
        else $color = $this->yellow;
        return $color.$string.$this->color_reset;
    }

    // Создаём файл "@current.txt", в котором хранится запись даты и времени на момент запуска (init)
    public function init() {
        $output = file_put_contents($this->current_fullpath, $this->date);
        if ($output !== false) return $this->set_color("\nФайл записан | {$this->current_fullpath}\n\n");
        else return $this->set_color("\nНе удалось записать файл | {$this->current_fullpath}\n\n", 1);
    }

    // Получаем дату из "@current.txt", если она меньше даты модификации файла, добавляем файл в массив модифицированных
    public function check() {
        if( !file_exists($this->current_fullpath) )
            return $this->set_color("\nФайл не найден, используйте параметр init | {$this->current_fullpath}\n\n", 1);

        $init_date = (int)file_get_contents($this->current_fullpath);
        $filenames = $this->scan_files($this->project_url);
        //$modified_files = array();
        $modified_files = "";
        $c = 0;

        foreach ($filenames as $value) {
            // $value["modified"] int
            if ( $value["modified"] > $init_date ) {
                $c++;
                $modified_files .= "{$c}. {$value['filename']}\n";
            }
        }

        if ( empty($modified_files) )
            return $this->set_color("\nФайлы не обновлялись с последней инициализации.\n\n");

        return "\n".$modified_files."\n\n";
    }

    // Удаляем файлы "@current.txt" и "@modified.txt"
    public function clear() {
        if ( !file_exists($this->current_fullpath) )
            return $this->set_color("\nФайл не найден | {$this->current_fullpath}\n\n", 1);

        if ( unlink($this->current_fullpath) )
            return $this->set_color("\nФайл удалён | {$this->current_fullpath}\n\n");

        //unlink($this->modified_fullpath);
    }
}

$fmc = new fmc();
$fmc->date = time();

if ( !isset($argv[1]) ) exit ("Не указаны параметры\n");

switch ($argv[1]) {
    case "init":
        echo $fmc->init();
        break;
    case "check":
        echo $fmc->check();
        break;
    case "clear":
        echo $fmc->clear();
        break;
    default:
        echo "Неправильный параметр, доступно init, check, clear\n";
}

?>
