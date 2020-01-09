<?php

class Comment extends Content
{
  private $postId;
  private $commentOrder;
  private $link;
  private $author;
  private $reports;

  public function postId()
  {
    return $this->postId;
  }

  public function commentOrder()
  {
    return $this->commentOrder;
  }

  public function link()
  {
    return $this->link;
  }

  public function author()
  {
    return $this->author;
  }

  public function reports()
  {
    return $this->reports;
  }

  public function reported()
  {
    if (isset($_SESSION["reportComment-".$this->id()])){
      return true;
    } else {
      return false;
    }
  }

  public function setPostId($postId)
  {
    $postId = (int) $postId;

    if ($postId > 0)
    {
      $this->postId = $postId;
    }
  }

  public function setCommentOrder($commentOrder)
  {
    $commentOrder = (int) $commentOrder;

    if ($commentOrder > 0)
    {
      $this->commentOrder = $commentOrder;
    }
  }

  public function setLink($page)
  {
    $page = (int) $page;

    if ($page > 0)
    {
      $this->link = 'index.php?action=post&id=' . $this->postId() . '&page=' . $page. '#comment-' . $this->id();
    }
  }

  public function setAuthor($author)
  {
    if (is_string($author))
    {
      $this->author = $author;
    }
  }

  public function setReports($reports)
  {
    (int) ($reports);
      $this->reports = $reports;
  }
}