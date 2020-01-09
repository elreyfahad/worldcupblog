<?php

class Post extends Content
{
  private $updateDateFr;
  private $commentsNumber;

  public function excerpt($readMore, $linkClass, $length = 400)
  {
    $string = $this->content();

    if (strlen(strip_tags($string)) < $length){
      return $string;
    }

    $i = 0;
    $tags = [];

    preg_match_all('/<[^>]+>([^<]*)/', $string, $tagMatches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach($tagMatches as $tagMatch) {
      if ($tagMatch[0][1] - $i >= $length) {
        break;
      }

      $tag = substr(strtok($tagMatch[0][0], " \t\n\r\0\x0B>"), 1);
      if ($tag[0] != '/') {
        $tags[] = $tag;
      }
      elseif (end($tags) == substr($tag, 1)) {
        array_pop($tags);
      }

      $i += $tagMatch[1][1] - $tagMatch[0][1];
    }

    return substr($string, 0, $length = min(strlen($string), $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '') . '... <a href="' . $this->link() . '" class="' . $linkClass . '">' . $readMore . '</a>';

  }

  public function updateDateFr()
  {
    return $this->updateDateFr;
  }

  public function commentsNumber()
  {
    return $this->commentsNumber;
  }

  public function link($criteria = null)
  {
    switch ($criteria) {
      case 'edit':
        return "admin.php?action=editPost&amp;id=" . $this->id();
        break;

      case 'delete':
        return "admin.php?action=deletePost&amp;id=" . $this->id();
        break;

      case 'editComments':
        return "admin.php?action=editComments&amp;id=" . $this->id();
        break;

      default:
        return "index.php?action=post&amp;id=" . $this->id();
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

  public function setUpdateDateFr($updateDateFr)
  {
    if (is_string($updateDateFr))
    {
      $this->updateDateFr = $updateDateFr;
    }
  }

  public function setCommentsNumber($commentsNumber)
  {
    $commentsNumber = (int) $commentsNumber;

    if ($commentsNumber > 0 )
    {
      $this->commentsNumber = $commentsNumber;
    }
  }
}