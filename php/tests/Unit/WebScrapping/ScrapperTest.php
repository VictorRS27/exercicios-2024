<?php

namespace Chuva\Tests\Unit\WebScrapping\WebScrapping;

use Chuva\Php\WebScrapping\Scrapper;
use PHPUnit\Framework\TestCase;
use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Tests requirements for Scrapper.
 */
class ScrapperTest extends TestCase {

  /**
   * Tests scrap count.
   */
  public function testScrapSignature() {

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument('1.0', 'utf-8');
    @$dom->loadHTMLFile(__DIR__ . '/test.html');

    libxml_clear_errors();


    $scrapper = new Scrapper();
    $results = $scrapper->scrap($dom);

    $expected[] = new Paper(
      0, 
      "Structural elucidation of a novel pyrrolizidine alkaloid isolated from Crotalaria retusa L.",
      "Poster Presentation",
      [
        new Person("Bryan Nickson Santana Pinto", "Departamento de Química, Centro de Ciências Exatas e Tecnológicas, Universidade Federal de Viçosa - Campus Viçosa"),
        new Person("Gabriella Almeida  Moura", "Departamento de Química, Centro de Ciências Exatas e Tecnológicas, Universidade Federal de Viçosa - Campus Viçosa"),
        new Person("Antonio Demuner", "Departamento de Química, Centro de Ciências Exatas e Tecnológicas, Universidade Federal de Viçosa - Campus Viçosa"),
        new Person("Elson Santiago Alvarenga", "Departamento de Química, Centro de Ciências Exatas e Tecnológicas, Universidade Federal de Viçosa - Campus Viçosa")
      ]
    );

    $expected[] = new Paper(
      1, 
      "INVERSE LAPLACE TRANSFORM FOR SIGNAL ANALYSIS OF LOW FIELD NUCLEAR MAGNETIC RESONANCE",
      "Poster Presentation",
      [
        new Person("Tiago   Bueno de Moraes", "Departamento de Química , Instituto de Ciências Exatas , Universidade Federal de Minas Gerais"),
      ]
    );

    $this->assertIsArray($results);
    $this->assertEquals($expected, $results);
  }

}
