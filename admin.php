<?php

require_once('Controller/Backend.php');
try
{
  $backend = new Backend();
  if($backend->loggedIn()){
    if (isset($_GET['action']))
    {
      $action = $_GET['action'];
      switch ($action){

        case 'newPost':
          if(isset($_POST['submit']) && isset($_POST['title']) && isset($_POST['content'])){
            $backend->insertPost(trim($_POST['title']), trim($_POST['content']));
          } else {
            $backend->newPost();
          }
          break;

        case 'editPost':
          if (isset($_GET['id']) && $_GET['id'] > 0) {
            if(isset($_POST['submit'])){
              if (isset($_POST['title']) && isset($_POST['content'])) {
                $backend->updatePost(intval($_GET['id']), trim($_POST['title']), trim($_POST['content']));
              } else {
                throw new Exception('Tous les champs ne sont pas remplis !');
              }
            } else {
              $backend->editPost(intval($_GET['id']));
            }
          } else {
            throw new Exception('Aucun identifiant d\'article envoyé');
          }
          break;

        case 'deletePost':
          if (isset($_GET['id']) && $_GET['id'] > 0) {
              $backend->deletePost(intval($_GET['id']));
              if(isset($_GET['redirect_to']) && $_GET['redirect_to'] == 'home'){
                $backend->returnMessage();
                $backend->setRedirection('index.php');
              } else {
                $backend->setRedirection('admin.php');
              }
          } else {
            throw new Exception('Aucun identifiant d\'article envoyé');
          }
          break;

        case 'editComments':
          if (isset($_GET['id']) && $_GET['id'] > 0) {
            $backend->editComments(intval($_GET['id']));
          } else {
            if (isset($_GET['reported']) && (int) $_GET['reported'] === 1){
              $backend->editComments('reported');
            } else {
              $backend->editComments('all');
            }
          }
          break;

        case 'editComment':
          if (isset($_GET['id']) && $_GET['id'] > 0) {
            if(isset($_POST['submit'])){
              if (isset($_POST['author']) && isset($_POST['comment'])) {
                $backend->updateComment(intval($_GET['id']), trim($_POST['author']), trim($_POST['comment']));
              } else {
                throw new Exception('Tous les champs ne sont pas remplis !');
              }
            } else {
              $backend->editComment(intval($_GET['id']));
            }
          } else {
            throw new Exception('Aucun identifiant de commentaire envoyé');
          }
          break;

        case 'deleteComment':
          if (isset($_GET['id']) && $_GET['id'] > 0) {
            $backend->deleteComment(intval($_GET['id']));
            if(isset($_GET['redirect_to'])){
              if($_GET['redirect_to'] === 'all'){
                $backend->setRedirection('admin.php?action=editComments');
              } elseif($_GET['redirect_to'] === 'reported'){
                $backend->setRedirection('admin.php?action=editComments&reported=1');
              } else{
                $backend->setRedirection('admin.php?action=editComments&id='.$_GET['redirect_to']);
              }
            } else {
              $backend->setRedirection('index.php');
            }
          } else {
            throw new Exception('Aucun identifiant de commentaire envoyé');
          }
          break;

        case 'multipleDelete':
          if (isset($_POST['type'])) {
            if(!isset($_POST['multipleDelete'])){
              $_POST['multipleDelete'] = 0;
            }
            if($_POST['type'] == 'post'){
              $backend->deletePosts($_POST['multipleDelete']);
            }elseif($_POST['type'] == 'comment'){
              $backend->deleteComments($_POST['multipleDelete']);
              if($_GET['redirect_to'] === 'all'){
                $backend->setRedirection('admin.php?action=editComments');
              } elseif($_GET['redirect_to'] === 'reported'){
                $backend->setRedirection('admin.php?action=editComments&reported=1');
              } else {
                $backend->setRedirection('admin.php?action=editComments&id='.$_GET['redirect_to']);
              }
            }
          } else {
            throw new Exception('Une erreur est survenue !');
          }
          break;

        case 'settings':
          if(isset($_POST['submit'])){
              if (isset($_POST['title']) && isset($_POST['description'])) {
                $itemsPerPage = "{$_POST['ppp']},{$_POST['cpp']},{$_POST['pppa']},{$_POST['cppa']}";
                $backend->updateSettings(trim($_POST['title']), trim($_POST['description']), $itemsPerPage);
              } else {
                throw new Exception('Tous les champs ne sont pas remplis !');
              }
          } else {
            $backend->editSettings();
          }
          break;

        case 'author':
          if(isset($_POST['submit'])){
            if (isset($_POST['author']) && isset($_POST['author_pseudo']) && isset($_POST['email'])) {
              $backend->updateAuthor(trim($_POST['author']), trim($_POST['author_pseudo']), trim($_POST['email']),trim($_POST['about_author']), $_POST['pass'], $_POST['pass2']);
            } else {
              throw new Exception('Tous les champs ne sont pas remplis !');
            }
          } else {
            $backend->editAuthor();
          }
          break;
      }
    }
    else
    {
      $backend->editPosts();
    }
  }
  else
  {
    $backend->setRedirection('index.php?action=login&redirect_to='.urlencode($_SERVER[REQUEST_URI]));
  }
  if($backend->redirection() !== false){
    header('location: ' . $backend->redirection());
  }
}
catch (Exception $e)
{
  $error = $e->getMessage();
  require('view/frontend/error.php');
}