<?php

namespace Chuva\Php\WebScrapping;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterFactory;
use Box\Spout\Common\Entity\Row;
/**
 * Runner for the Webscrapping exercice.
 */
class Main {


  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    libxml_clear_errors();

    $data = (new Scrapper())->scrap($dom);

    // Write your logic to save the output file bellow.
    
    //print_r($data);
    $filename = __DIR__ . '/../../assets/result.xlsx';
    $writer = WriterFactory::createFromType('xlsx');
    $writer->openToFile($filename);

    // Definindo os cabeçalhos das colunas
    $headers = ['ID', 'Title', 'Type', 'Author1', 'Institution1', 'Author2', 'Institution2', 'Author3', 'Institution3', 'Author4', 'Institution4', 'Author5', 'Institution5', 'Author6', 'Institution6', 'Author7', 'Institution7', 'Author8', 'Institution8', 'Author9', 'Institution9'];
    $rowFromValues = WriterEntityFactory::createRowFromArray($headers);
    $writer->addRow($rowFromValues);

    // Adicionando os dados
    foreach ($data as $paper) {
      $tmp_row = [
          $paper->id,
          $paper->title,
          $paper->type
      ];

      // Adiciona autores e suas instituições
      for ($i = 0; $i < 9; $i++) {
          if (isset($paper->authors[$i])) {
              $tmp_row[] = $paper->authors[$i]->name;
              $tmp_row[] = $paper->authors[$i]->institution;
          } else {
              $tmp_row[] = '';
              $tmp_row[] = '';
          }
      }

      $rowFromValues = WriterEntityFactory::createRowFromArray($tmp_row);
      $writer->addRow($rowFromValues);
    }

    // Fecha o writer
    $writer->close();

    echo "Dados exportados para o arquivo: $filename\n";
  }
  

}
