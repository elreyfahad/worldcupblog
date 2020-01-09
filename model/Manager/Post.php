<?php
class PostManager extends ContentManager
{
  protected $table = 'posts';

  public function getPosts($currentPage = 0, $itemsPerPage = 0)
  {
    $posts = [];
    $this->countPosts();
    $db = $this->dbConnect();
    if($currentPage === 0){
      $currentPage = $this->currentPage();
    }
    if($itemsPerPage === 0){
      $itemsPerPage = $this->itemsPerPage();
    }

    $req = $db->prepare('SELECT id, title, content, comments_number, DATE_FORMAT(creation_date, \'%d/%m/%Y à %H:%i\') AS date_fr, DATE_FORMAT(update_date, \'%d/%m/%Y à %H:%i\') AS update_date_fr FROM posts ORDER BY creation_date DESC LIMIT ?, ?');
    $req->execute([($currentPage-1)*$itemsPerPage, $itemsPerPage]);

    foreach ($req->fetchAll() as $data)
    {
      $posts[] = new Post(['id'=>$data['id'],
                'title'=>$data['title'],
                'content'=>$data['content'],
                'dateFr'=>$data['date_fr'],
                'updateDateFr'=>$data['update_date_fr'],
                'commentsNumber'=>$data['comments_number']]);
    }
    return $posts;
  }

  public function countPosts()
  {
    $db = $this->dbConnect();
    $req = $db->prepare("SELECT count(*) FROM posts");
    $req->execute();
    $postsNumber = $req->fetchColumn();
    $this->setCountItems($postsNumber);
  }

  public function getPost($postId)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, title, content, comments_number, DATE_FORMAT(creation_date, \'%d/%m/%Y à %H:%i\') AS date_fr, DATE_FORMAT(update_date, \'%d/%m/%Y à %H:%i\') AS update_date_fr FROM posts WHERE id = ?');
    $req->execute([$postId]);

    $data = $req->fetch();
    if($data){
      $post = new Post(['id'=>$data['id'],
              'title'=>$data['title'],
              'content'=>$data['content'],
              'dateFr'=>$data['date_fr'],
              'updateDateFr'=>$data['update_date_fr'],
              'commentsNumber'=>$data['comments_number']]);
      return $post;
    }
    return false;
  }

  public function insertPost(Post $post)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('INSERT INTO posts(title, content, creation_date, update_date) VALUES(?, ?, NOW(), NOW())');
    $req->execute([$post->title(), $post->content()]);

    $lastInsertId = $db->lastInsertId();

    return $lastInsertId;
  }

  public function addComment($postId)
  {
    $db = $this->dbConnect();
        $req = $db->prepare('UPDATE posts SET comments_number = comments_number+1 WHERE id = ?');
        $executeResult = $req->execute([$postId]);
        return $executeResult;
  }

  public function deleteComment($postId)
  {
    $db = $this->dbConnect();
        $req = $db->prepare('UPDATE posts SET comments_number = comments_number-1 WHERE id = ?');
        $executeResult = $req->execute([$postId]);
        return $executeResult;
  }

  public function updatePost(Post $post)
  {
      $db = $this->dbConnect();
    $req = $db->prepare('UPDATE posts SET title = ?, content = ?, update_date = NOW() WHERE id = ?');
    $req->execute([$post->title(), $post->content(), $post->id()]);

    $lastInsertId = $db->lastInsertId();

    return $lastInsertId;
  }

}