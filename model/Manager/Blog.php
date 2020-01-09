<?php
class BlogManager extends Manager
{

  public function getSettings()
  {
    $db = $this->dbConnect();
    $req = $db->prepare("SELECT id, title, description, items_per_page FROM settings");
    $req->execute();
    $data = $req->fetch(PDO::FETCH_ASSOC);
    $blog = new Blog($data);
    return $blog;
  }

  public function setSettings(Blog $blog)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('UPDATE settings SET title = ?, description = ?, items_per_page = ? WHERE id = 1');
    $executeResult = $req->execute([$blog->title(), $blog->description(), $blog->itemsPerPage()]);

    return $executeResult;
  }

}