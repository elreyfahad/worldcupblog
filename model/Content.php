<?php

class Content
{
  private $id;
  private $content;
  private $dateFr;

  public function __construct(array $data)
  {
    $this->hydrate($data);
  }

  public function hydrate(array $data)
  {
    foreach ($data as $key => $value)
    {
      $method = 'set'.ucfirst($key);

      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }

  public function setId($id)
  {
    $id = (int) $id;

    if ($id > 0)
    {
      $this->id = $id;
    }
  }

  public function id()
  {
    return $this->id;
  }

  public function title()
  {
    return $this->title;
  }

  public function content()
  {
    return $this->content;
  }

  public function dateFr()
  {
    return $this->dateFr;
  }

  public function setContent($content)
  {
    if (is_string($content))
    {
      $this->content = $content;
    }
  }

  public function setDateFr($dateFr)
  {
    if (is_string($dateFr))
    {
      $this->dateFr = $dateFr;
    }
  }

}