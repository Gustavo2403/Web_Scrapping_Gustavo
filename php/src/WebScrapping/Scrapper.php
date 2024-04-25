<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Faz a raspagem de uma página da web.
 */
class Scrapper {

  /**
   * Carrega informações do papel a partir do HTML e retorna um array com os dados.
   */
  public function scrap(\DOMDocument $dom): array {
    $xPath = new \DOMXPath($dom);
    $cardNodes = $xPath->query('//a[@class="paper-card p-lg bd-gradient-left"]');
    $titleNodes = $xPath->query('//a[@class="paper-card p-lg bd-gradient-left"]/h4[@class="my-xs paper-title"]');
    $typeNodes = $xPath->query('//div[@class="tags mr-sm"]');
    $authorsNodes = $xPath->query('//div[@class="authors"]');

    // Inicializa um array para armazenar os papers.
    $papers = [];

    // Itera sobre os nós encontrados.
    foreach ($titleNodes as $index => $tituloNode) {
      // Extrai o ID do href do elemento do paper.
      $href = $cardNodes[$index]->getAttribute('href');
      $id = substr($href, strrpos($href, '/') + 1);

      // Extrai o título e tipo do paper.
      $title = $tituloNode->textContent;
      $type = $typeNodes[$index]->textContent;

      // Extrai os autores e as instituições.
      $authors = explode(';', $authorsNodes[$index]->textContent);
      $institutions = $authorsNodes[$index]->getElementsByTagName('span');

      // Inicializa um array para armazenar as instâncias de Person.
      $persons = [];

      // Itera sobre os autores e cria instâncias de Person.
      foreach ($authors as $key => $author) {
        // Extrai o nome do autor.
        $authorName = trim($author);
        // Extrai a instituição correspondente ao autor, se existir.
        $institution = isset($institutions[$key]) ? $institutions[$key]->getAttribute('title') : null;
        // Verifica se o nome do autor não está vazio e se há uma instituição correspondente.
        if (!empty($authorName) && !empty($institution)) {
          // Cria uma instância de Person com o nome do autor e a instituição.
          $persons[] = new Person($authorName, $institution);
        }
      }

      // Cria uma nova instância de Paper e adiciona ao array de papers.
      $papers[] = new Paper($id, $title, $type, $persons);
    }

    // Retorna o array de papers.
    return $papers;
  }
}
