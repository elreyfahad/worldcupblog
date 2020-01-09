<?php
class ContentManager extends Manager
{
  protected $table;
  protected $countItems;
  protected $end;

  public function __construct($end = 'back'){
    $this->end = $end;
  }

  public function itemsPerPage($end = null){
    if(!isset($end)){
      $end = $this->end;
    }
    $db = $this->dbConnect();
    $req = $db->query('SELECT items_per_page FROM settings WHERE id = 1');
    $data = $req->fetch();
    $itemsPerPage = explode(',', $data['items_per_page']);

    if ($this->table() == 'posts'){
      if($end == 'front'){
        return $itemsPerPage[0];
      }elseif($end == 'back'){
        return $itemsPerPage[2];
      }
    } elseif ($this->table() == 'comments'){
      if($end == 'front'){
        return $itemsPerPage[1];
      }elseif($end == 'back'){
        return $itemsPerPage[3];
      }
    }
  }

  public function table(){
    return $this->table;
  }

  public function setItemsPerPage($int){
    $int = (int) $int;
    $this->itemsPerPage = $int;
  }

  public function deleteContent(Content $content)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('DELETE FROM ' . $this->table() . ' WHERE id = ?');
    $executeResult = $req->execute([$content->id()]);

    return $executeResult;
  }

  public function currentPage()
  {
    if(isset($_GET['page'])){
      $page = intval($_GET['page']);
      if($page <= 1){
        $page = 1;
      }
    } else {
      $page = 1;
    }
    return $page;
  }

  public function setCountItems($countItems)
  {
    $countItems = (int) $countItems;
    $this->countItems = $countItems;
  }

  public function countItems()
  {
    return $this->countItems;
  }

  public function pagination($path, $class = 'pagination'){

    $page = $this->currentPage();
    $itemsPerPage = $this->itemsPerPage();
    $items = $this->countItems;
    $pages = ceil($items/$itemsPerPage);
    $pagination = '';
    $path .= 'page=';

    if ($pages > 1){
      $pagination .= '<ul class="' . $class . '">';
        if ($page > 1){
          $pagination .= '<li><a href="' . $path. ($page - 1) . '">&laquo;</a></li>';
        }

        for ($i = 1; $i <= $pages; $i++){

          if($i > $page){

          }

          if($i === $page){
            $pagination .= '<li class="active"><a>' . $i . '</a></li>';
          } elseif (($i > $page-2 && $i < $page+2) || $i < 2 || $i > $pages-1 ) {
            $pagination .= '<li><a href="' . $path . $i . '">' . $i . '</a></li>';
          } elseif ($i == 2  || $i == $pages-1){
            $pagination .= '<li><a>...</a></li>';
          }

        }

        if ($page < $pages){
          $pagination .= '<li><a href="' . $path . ($page + 1) . '">&raquo;</a></li>';
        }
      $pagination .= '</ul>';
      return $pagination;
    }
    return false;
  }

}