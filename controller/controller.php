<?php

require('Model/Blog.php');
require('Model/Content.php');
require('Model/Post.php');
require('Model/Author.php');
require('Model/Comment.php');

require('Model/Manager/Manager.php');
require('Model/Manager/Blog.php');
require('Model/Manager/Content.php');
require('Model/Manager/Post.php');
require('Model/Manager/Author.php');
require('Model/Manager/Comment.php');

class Controller
{
  protected $redirection = false;
  protected $formError = false;

  public function getBlog()
  {
    $blogManager = new BlogManager();
    return $blogManager->getSettings();
  }

  public function loggedIn()
  {
    if(isset($_SESSION['id']) && isset($_SESSION['pseudo'])){
      return true;
    } else {
      return false;
    }
  }

  public function setRedirection($redirection)
  {
    $this->redirection = $redirection;
  }

  public function redirection()
  {
    return $this->redirection;
  }

  public function setReturnMessage($type,$message)
  {
    $_SESSION['returnMessage'] = ['type' => $type, 'message' => $message];
    $this->returnMessage = true;
  }

  public function returnMessage()
  {
    if (isset($_SESSION['returnMessage'])){
      $returnMessage = $_SESSION['returnMessage'];
      unset($_SESSION['returnMessage']);
      return $returnMessage;
    } else {
      return false;
    }
  }

  public function setFormError($field, $error)
  {
    $_SESSION['formError'][$field][] = $error;
    $this->formError = true;
  }

  public function formError($field){
    if (isset($_SESSION['formError'][$field])){
      $formError = $_SESSION['formError'][$field];
      unset($_SESSION['formError'][$field]);
      $return = '<ul class="text-danger">';
      foreach($formError as $error){
        $return .= "<li>$error</li>";
      }
      $return .= '</ul>';
      return $return;
    } else {
      return false;
    }
  }
}