<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $xpath = new \DOMXPath($dom);

    $id_counter = 0;
    $papers = [];
    //percorre os cards com papers
    $nodes = $xpath->query('//*[contains(@class, "paper-card")]');
    $count = 0;
    foreach ($nodes as $node) {
        //pega o título de cada paper
        $subtitle_h4 = $xpath->query('.//h4', $node)->item(0); 
        $title = $subtitle_h4 ? $subtitle_h4->textContent : '';

        //Leitura dos autores
        $spans = $xpath->query('.//span', $node);
        $persons = [];

        foreach ($spans as $span) {
          //nomes
          $author = $span->textContent;
          $author = str_replace(";", "", $author);

          //universidade
          $titleAttr = $span->getAttribute('title');
          $titleAttr = str_replace(";", "", $titleAttr);
          $titleAttr = str_replace(" /", ",", $titleAttr);
          $univ = $titleAttr;
          
          if (!empty($author) and !empty($univ)) {
            $persons[]= new Person($author, $univ);    
          }
        }
        
        //pegando o tipo do paper
        $div_with_type = $xpath->query('.//div[contains(@class, "tags mr-sm")]', $node)->item(0);
        if ($div_with_type) {
            $type = $div_with_type->textContent;
        }

        //criando objeto Paper
        $papers[] = new Paper(
          $id_counter,
          $title,
          $type,
          $persons
        );
        $id_counter++;
    }

    return $papers;
  }
  
}
