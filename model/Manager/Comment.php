<?php
class CommentManager extends ContentManager
{
  protected $table = 'comments';

  public function getComments($criteria, $currentPage = 0, $itemsPerPage = 0)
  {
    $comments = [];
    $this->countComments($criteria);
    $db = $this->dbConnect();
    if($currentPage === 0){
      $currentPage = $this->currentPage();
    }
    if($itemsPerPage === 0){
      $itemsPerPage = $this->itemsPerPage();
    }

    switch($criteria) {
      case 'all':
        $req = $db->prepare('SELECT id, author, post_id, comment_order, comment, reports, DATE_FORMAT(comment_date, \'%d/%m/%Y à %H:%i\') AS date_fr FROM comments ORDER BY comment_date ASC LIMIT ?, ?');
        $req->execute([($currentPage-1)*$itemsPerPage, $itemsPerPage]);
      break;

      case 'reported':
        $req = $db->prepare('SELECT id, author, post_id, comment_order, comment, reports, DATE_FORMAT(comment_date, \'%d/%m/%Y à %H:%i\') AS date_fr FROM comments WHERE reports > 0 ORDER BY comment_date ASC LIMIT ?, ?');
        $req->execute([($currentPage-1)*$itemsPerPage, $itemsPerPage]);
      break;

      default:
        $req = $db->prepare('SELECT id, author, post_id, comment_order, comment, reports, DATE_FORMAT(comment_date, \'%d/%m/%Y à %H:%i\') AS date_fr FROM comments WHERE post_id = ? ORDER BY comment_date ASC LIMIT ?, ?');
        $req->execute([$criteria, ($currentPage-1)*$itemsPerPage, $itemsPerPage]);
      break;
    }


    foreach ($req->fetchAll() as $data)
    {
      $comments[] = new Comment(['id'=>$data['id'],
                'author'=>$data['author'],
                'postId'=>$data['post_id'],
                'commentOrder'=>$data['comment_order'],
                'link'=>ceil($data['comment_order']/$this->itemsPerPage('front')),
                'dateFr'=>$data['date_fr'],
                'reports'=>$data['reports'],
                'content'=>$data['comment']]);
    }

        return $comments;
  }

  public function countComments($criteria)
  {
        $db = $this->dbConnect();

    switch($criteria){
      case 'all':
        $req = $db->prepare("SELECT count(*) FROM comments");
        $req->execute();
        break;

      case 'reported':
        $req = $db->prepare("SELECT count(*) FROM comments WHERE reports > 0");
        $req->execute();
        break;

      default :
        $req = $db->prepare("SELECT count(*) FROM comments WHERE post_id = ?");
        $req->execute(array($criteria));
        break;
    }

    $commentsNumber = $req->fetchColumn();
    $this->setCountItems($commentsNumber);
  }

  public function deletePostComments($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE post_id = ?');
        $executeResult = $req->execute(array($postId));

    return $executeResult;
    }

  public function newComment(Comment $comment)
  {
    $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO comments(post_id, comment_order, author, comment, comment_date) VALUES(?, ?, ?, ?, NOW())');
    $executeResult = $req->execute([$comment->postId(),$comment->commentOrder(),$comment->author(),$comment->content()]);
    $lastInsertId = $db->lastInsertId();
    return $lastInsertId;
  }

  public function updateComment(Comment $comment)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comments SET author = ?, comment = ? WHERE id = ?');
    $executeResult = $req->execute([$comment->author(), $comment->content(), $comment->id()]);

    return $executeResult;
    }

  public function getComment($commentId)
  {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, post_id, author, comment, comment_order, reports, DATE_FORMAT(comment_date, \'%d/%m/%Y à %H:%i\') AS date_fr FROM comments WHERE id = ?');
        $req->execute(array($commentId));

    $data = $req->fetch();
    if($data){

      $comment = new Comment(['id'=>$data['id'],
                  'postId'=>$data['post_id'],
                  'commentOrder'=>$data['comment_order'],
                  'link'=>ceil($data['comment_order']/$this->itemsPerPage('front')),
                  'author'=>$data['author'],
                  'content'=>$data['comment'],
                  'dateFr'=>$data['date_fr'],
                  'reports'=>$data['reports']]);

      return $comment;
    }
    return false;
  }

  public function reportComment(Comment $comment)
  {
    $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comments SET reports = reports+1 WHERE id = ?');
        $executeResult = $req->execute([$comment->id()]);
    return $executeResult;
  }

}