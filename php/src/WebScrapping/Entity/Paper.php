<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * The Paper class represents the row of the parsed data.
 */
class Paper {

  /**
   * Paper Id.
   *
   * @var int
   */
  public $id;

  /**
   * Paper Title.
   *
   * @var string
   */
  public $title;

  /**
   * The paper type (e.g. Poster, Nobel Prize, etc).
   *
   * @var string
   */
  public $type;

  /**
   * Paper authors.
   *
   * @var \Chuva\Php\WebScrapping\Entity\Person[]
   */
  public $authors;

  /**
   * Builder.
   *
   * @param int $id
   *   The paper Id.
   * @param string $title
   *   The paper title.
   * @param string $type
   *   The paper type.
   * @param array $authors
   *   An array of Person objects representing the authors.
   */
  public function __construct($id, $title, $type, $authors = []) {
    $this->id = $id;
    $this->title = $title;
    $this->type = $type;
    $this->authors = $authors;
  }
   /**
   * Get the paper Id.
   *
   * @return int
   *   The paper Id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get the paper title.
   *
   * @return string
   *   The paper title.
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Get the paper type.
   *
   * @return string
   *   The paper type.
   */
  public function getType() {
    return $this->type;
  }

   /**
   * Get the authors of the paper.
   *
   * @return \Chuva\Php\WebScrapping\Entity\Person[]
   *   An array of Person objects representing the authors.
   */
  public function getPersons() {
    return $this->authors;
  }

}
