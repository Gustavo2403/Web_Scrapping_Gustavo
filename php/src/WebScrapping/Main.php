<?php

namespace Chuva\Php\WebScrapping;
require_once __DIR__ . '/../webscrapping/Scrapper.php';
require_once 'vendor/box/spout/src/Spout/Autoloader/autoload.php';
require 'vendor/autoload.php';
use Chuva\Php\WebScrapping\Scrapper;
use PHPUnit\Framework\TestCase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/*
class Main {
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);
    var_dump($data);
    // Write your logic to save the output file bellow.
    print_r($data);
  }

} */


class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    // Write your logic to save the output file bellow.
    print_r($data);
  }

}
