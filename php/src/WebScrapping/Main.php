<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Scrapper;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '../../../vendor\autoload.php';

/**
 * Classe de teste para Main.
 */
class Main extends TestCase {

  /**
   * Teste principal.
   */
  public function test() {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');
    $scrapper = new Scrapper();
    $results = $scrapper->scrap($dom);

    $this->assertIsArray($results);

    // Crie uma instância do objeto Spreadsheet.
    $spreadsheet = new Spreadsheet();

    // Obtenha a planilha ativa.
    $sheet = $spreadsheet->getActiveSheet()->setTitle('Sheet1');

    // Inicialize o contador para o número máximo de autores encontrados.
    $maxAuthors = 0;

    // Iterar sobre cada resultado para encontrar o número máximo de autores.
    foreach ($results as $result) {
      $numberOfAuthors = count($result->getAuthors());
      if ($numberOfAuthors > $maxAuthors) {
        $maxAuthors = $numberOfAuthors;
      }
    }

    // Cabeçalho das colunas.
    $headerCells = ['ID', 'Title', 'Type'];

    for ($i = 1; $i <= $maxAuthors; $i++) {
      $headerCells[] = 'Author ' . $i;
      $headerCells[] = 'Author ' . $i . ' Institution';
    }

    // Adicione o cabeçalho.
    $sheet->fromArray([$headerCells]);

    // Adicione os estilos para o cabeçalho.
    $headerStyle = [
      'font' => [
        'name' => 'Arial',
        'bold' => TRUE,
        'size' => 10,
      ],

      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_TOP,
        'wrapText' => TRUE,
      ],
    ];

    $bodyStyle = [
      'font' => [
        'name' => 'Arial',
        'size' => 10,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_TOP,
        'wrapText' => TRUE,
      ],
    ];

    $idStyle = [
      'font' => [
        'name' => 'Arial',
        'size' => 10,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_RIGHT,
        'vertical' => Alignment::VERTICAL_TOP,
        'wrapText' => TRUE,
      ],
    ];

    // Coordenada da última coluna.
    $lastColumn = Coordinate::stringFromColumnIndex(52);
    $range = 'A1:' . $lastColumn . '1';
    $sheet->getStyle($range)->applyFromArray($headerStyle);

    // Adicione os dados de cada resultado.
    foreach ($results as $rowIndex => $result) {
      $rowData = [
        $result->getId(), $result->getTitle(), $result->getType(),
      ];

      // Adicione as informações de autor e instituição.
      foreach ($result->getAuthors() as $person) {
        $rowData[] = $person->getName();
        $rowData[] = $person->getInstitution();
      }

      // Preencha com células vazias se necessário.
      $remainingCells = 40 - count($rowData);
      for ($i = 0; $i < $remainingCells; $i++) {
        $rowData[] = '';
      }

      // Adicione a linha na planilha.
      $sheet->fromArray([$rowData], NULL, 'A' . ($rowIndex + 2));

      // Aplicar o estilo idStyle apenas à coluna A.
      $sheet->getStyle('A' . ($rowIndex + 2))->applyFromArray($idStyle);

      // Aplicar o estilo bodyStyle às outras colunas.
      $lastColumn = Coordinate::stringFromColumnIndex(52);
      $bodyRange = 'B' . ($rowIndex + 2) . ':' . $lastColumn . ($rowIndex + 2);
      $sheet->getStyle($bodyRange)->applyFromArray($bodyStyle);
    }

    // Defina a largura das colunas A e B.
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(45);
    $sheet->getColumnDimension('C')->setWidth(20);

    $lastColumnIndex = 52;
    for ($columnIndex = 4; $columnIndex <= $lastColumnIndex; $columnIndex++) {
      $columnName = Coordinate::stringFromColumnIndex($columnIndex);
      $sheet->getColumnDimension($columnName)->setWidth(28);
    }

    // Crie um objeto Writer para salvar a planilha em um arquivo Excel.
    $writer = new Xlsx($spreadsheet);

    // Especifique o caminho do arquivo onde você deseja salvar a planilha.
    $filePath = (__DIR__ . '../../../assets\model.xlsx');

    // Salve a planilha no arquivo especificado.
    $writer->save($filePath);
  }

}
