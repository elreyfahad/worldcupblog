<?php

class Blog
{
  private $id;
  private $title;
  private $description;
  private $itemsPerPage;

  public function __construct(array $data)
  {
    $this->hydrate($data);
  }

  public function hydrate(array $data)
  {
    foreach ($data as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      $method = str_replace('_','',$method);

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

  public function description()
  {
    return $this->description;
  }

  public function itemsPerPage($criteria = null)
  {
    $itemsPerPage = explode(',', $this->itemsPerPage);
    switch ($criteria)
    {
      case 'ppp':
          return $itemsPerPage[0];
        break;

      case 'cpp':
          return $itemsPerPage[1];
          break;

      case 'pppa':
          return $itemsPerPage[2];
        break;

      case 'cppa':
          return $itemsPerPage[3];
        break;

      default:
        return $this->itemsPerPage;
        break;
    }
  }

  public function setTitle($title)
  {
    if (is_string($title))
    {
      $this->title = $title;
    }
  }
  
  public function setDescription($description)
  {
    if (is_string($description))
    {
      $this->description = $description;
    }
  }

  public function setItemsPerPage($itemsPerPage)
  {
    if (is_string($itemsPerPage))
    {
      $this->itemsPerPage = $itemsPerPage;
    }
  }
}