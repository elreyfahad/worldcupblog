<?php

class Author
{
  private $id;
  private $name;
  private $pseudo;
  private $email;
  private $pass;
  private $about;

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
  
  public function name()
  {
    return $this->name;
  }

  public function pseudo()
  {
    return $this->pseudo;
  }

  public function email()
  {
    return $this->email;
  }

  public function pass()
  {
    return $this->pass;
  }

  public function about()
  {
    return $this->about;
  }
  
  public function setName($name)
  {
    if (is_string($name))
    {
      $this->name = $name;
    }
  }
  
  public function setPseudo($pseudo)
  {
    if (is_string($pseudo))
    {
      $this->pseudo = $pseudo;
    }
  }

  public function setEmail($email)
  {
    if (is_string($email))
    {
      $this->email = $email;
    }
  }

  public function setPass($pass)
  {
    if (is_string($pass))
    {
      $this->pass = $pass;
    }
  }

  public function setAbout($about)
  {
    if (is_string($about))
    {
      $this->about = $about;
    }
  }
}