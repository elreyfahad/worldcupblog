<?php
class AuthorManager extends Manager
{
  public function getAuthor()
  {
    $db = $this->dbConnect();
    $req = $db->prepare("SELECT id, author, author_pseudo, email, pass, about_author FROM settings");
    $req->execute();
    $data = $req->fetch();

    $author = new Author(['id'=>$data['id']]);
    $author->setName($data['author']);
    $author->setPseudo($data['author_pseudo']);
    $author->setEmail($data['email']);
    $author->setPass($data['pass']);
    $author->setAbout($data['about_author']);

    return $author;
  }

  public function setAuthor(Author $author)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('UPDATE settings SET author = ?, author_pseudo = ?, email = ?, pass = ?, about_author = ? WHERE id = 1');
    $executeResult = $req->execute([$author->name(), $author->pseudo(), $author->email(), $author->pass(), $author->about()]);

    return $executeResult;
  }
}