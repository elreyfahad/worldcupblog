<?php
require_once('Controller/Controller.php');

class Backend extends Controller
{
  function editPosts()
  {
    $blog = $this->getBlog();
    $postManager = new PostManager();

    $posts = $postManager->getPosts();

    $pagination = $postManager->pagination("admin.php?");

    require('view/backend/postsEdit.php');
  }

  function newPost()
  {
    $title = 'Nouvel article';
    $action = 'admin.php?action=newPost';
    $submit = 'Publier';
    if (isset($_SESSION['post'])){
      $post = $_SESSION['post'];
      unset($_SESSION['post']);
    }
    require('view/backend/postEdit.php');
  }

  function insertPost($title, $content)
  {
    $postManager = new PostManager();
    $post = new Post(['title'=>$title, 'content'=>$content]);
    $error = false;

    if(empty($title)){
      $this->setFormError('title','Le champ de titre est obligatoire');
    }

    if($this->formError){
      $error = true;
      $this->setReturnMessage('danger', 'Veuillez corriger les erreurs');
    } else {
      $lastInsertId = $postManager->insertPost($post);
      if($lastInsertId === '0'){
        $error = true;
        $this->setReturnMessage('danger', 'Impossible d\'ajouter l\'article !');
      }
    }

    if ($error) {
      $_SESSION['post'] = $post;
      $this->newPost();
    }
    else {
      $this->setReturnMessage('success', 'Article publié avec succès <a href="index.php?action=post&id='.$lastInsertId.'">Afficher</a>');
      $this->setRedirection('admin.php?action=editPost&id=' . $lastInsertId);
    }
  }

  function editPost($postId)
  {
    $postManager = new PostManager();
    $post = $postManager->getPost($postId);
    if($post){
      $title = 'Modifier l\'article';
      $action = $post->link('edit');
      $submit = 'Mettre à jour';
      require('view/backend/postEdit.php');
    } else {
      throw new Exception('Identifiant d\'article introuvable');
    }
  }

  function updatePost($postId, $title, $content)
  {
    $postManager = new PostManager();
    $post = $postManager->getPost($postId);

    $post->setTitle($title);
    $post->setContent($content);

    $executeResult = $postManager->updatePost($post);

    if ($executeResult === false) {
      $this->setReturnMessage('danger', 'Impossible de modifier l\'article !');
    }
    else {
      $this->setReturnMessage('success', 'Article mis à jour avec succès <a href="index.php?action=post&id='.$postId.'">Afficher</a>');
    }
    $this->editPost($post->id());
  }

  function deletePost($postId)
  {
    $postManager = new PostManager();
    $post = $postManager->getPost($postId);
    if($post){
      $executeResult = $postManager->deleteContent($post);

      if ($executeResult === false) {
        $this->setReturnMessage('danger', 'Impossible de supprimer l\'article !');
      }
      else {
        $commentManager = new commentManager();
        $affectedRows = $commentManager->deletePostComments($postId);
        if ($affectedRows === false) {
          $this->setReturnMessage('danger', 'Impossible de supprimer les commentaires d\'article !');
        }
        else {
          $this->setReturnMessage('success', 'Article supprimé avec succès');
        }
      }
    } else {
      throw new Exception('Identifiant d\'article introuvable');
    }
  }

  function deletePosts($listIds)
  {
    if(is_array($listIds)){
      foreach($listIds as $postId){
        $this->deletePost($postId);
      }
      $this->setReturnMessage('success', count($listIds) . ' article(s) supprimé(s) avec succès');
    }else{
      $this->setReturnMessage('danger', 'Aucun article n\'est sélectionné');
    }
    $this->setRedirection('admin.php');
  }

  function editComments($criteria)
  {
    $commentManager = new CommentManager();
    $comments = $commentManager->getComments($criteria);

    if (is_int($criteria)){
      $postManager = new PostManager();
      $post = $postManager->getPost($criteria);
      if ($post){
        $h1 = "Modérer les commentaires sur " . htmlspecialchars($post->title());
        $redirectTo = $post->id();
        $pagination = $commentManager->pagination("admin.php?action=editComments&id=$criteria&");
      } else {
        throw new Exception('Identifiant d\'article introuvable');
      }
    }

    if ($criteria === 'all') {
      $h1 = "Modérer les commentaires";
      $redirectTo = 'all';
      $pagination = $commentManager->pagination("admin.php?action=editComments&");
    } else if ($criteria === 'reported'){
      $h1 = "Modérer les commentaires signalés";
      $redirectTo = 'reported';
      $pagination = $commentManager->pagination("admin.php?action=editComments&reported=1&");
    }

    require('view/backend/commentsEdit.php');
  }

  function editComment($commentId)
  {
    $commentManager = new CommentManager();
    $comment = $commentManager->getComment($commentId);
    if ($comment) {
      require('view/backend/commentEdit.php');
    } else {
      throw new Exception('Identifiant de commentaire introuvable');
    }
  }

  function updateComment($commentId,$author,$content)
  {
    $commentManager = new CommentManager();
    $comment = new Comment(['id'=>$commentId, 'author'=>$author, 'content'=>$content]);

    $executeResult = $commentManager->updateComment($comment);

    if ($executeResult === false) {
      $this->setReturnMessage('danger', 'Impossible de modifier le commentaire !');
    }
    else {
      $this->setReturnMessage('success', 'Commentaire modifié avec succès');
    }
    $this->editComment($comment->id());
  }

  function deleteComment($commentId)
  {
    $commentManager = new CommentManager();
    $comment = $commentManager->getComment($commentId);
    if ($comment){
      $executeResult = $commentManager->deleteContent($comment);
      if ($executeResult === false) {
        $this->setReturnMessage('danger', 'Impossible de supprimer le commentaire !');
      }
      else {
        $postManager = new PostManager();
        $postManager->deleteComment($comment->postId());
        $this->setReturnMessage('success', 'Commentaire supprimé avec succès');
      }
    } else {
      throw new Exception('Identifiant de commentaire introuvable');
    }
  }

  function deleteComments($listIds)
  {
    if(is_array($listIds)){
      foreach($listIds as $commentId){
        $this->deleteComment($commentId);
      }
      $this->setReturnMessage('success', count($listIds) . ' commentaire(s) supprimé(s) avec succès');
    }else{
      $this->setReturnMessage('danger', 'Aucun commentaire n\'est sélectionné');
    }
  }

  function editSettings()
  {
    $blog = $this->getBlog();
    require('view/backend/settings.php');
  }

  function editAuthor()
  {
    if(isset($_SESSION['author'])){
      $author = $_SESSION['author'];
      unset($_SESSION['author']);
    } else {
      $authorManager = new AuthorManager();
      $author = $authorManager->getAuthor();
    }
    require('view/backend/author.php');
  }

  function updateSettings($title, $description, $itemsPerPage)
  {

    $blogManager = new BlogManager();
    $blog = $blogManager->getSettings();
    $blog->setTitle($title);
    $blog->setDescription($description);
    $blog->setItemsPerPage($itemsPerPage);

    $executeResult = $blogManager->setSettings($blog);

    if ($executeResult === false) {
      $this->setReturnMessage('danger', 'Impossible de modifier les réglages !');
    }
    else {
      $this->setReturnMessage('success', 'Réglages mis à jour avec succès');
    }
    $this->editSettings();
  }

  function updateAuthor($name, $pseudo, $email, $about, $pass, $pass2)
  {
    $authorManager = new AuthorManager();
    $author = $authorManager->getAuthor();
    $author->setName($name);
    $author->setPseudo(strtolower($pseudo));
    $author->setEmail($email);
    $author->setAbout($about);

    if(!ctype_alnum($pseudo)){
      $this->setFormError('author_pseudo', 'Le pseudo ne doit contenir que des lettres non accentuées et chiffres.');
    }

    if(mb_strlen($pseudo) < 4){
      $this->setFormError('author_pseudo', 'Le pseudo doit contenir 4 caractères au minimum.');
    }

    if((!empty($pass) || !empty($pass2))){
      if((!empty($pass) || !empty($pass2)) && $pass !== $pass2){
        $this->setFormError('pass', 'Les deux mots de passe ne correspondent pas !');
      }
      if(mb_strlen($pass) < 6 || (mb_strlen($pass2) < 6)){
        $this->setFormError('pass', 'Le mot de pass doit contenir 6 caractères au minimum.');
      }
    }

    if($this->formError){
      $this->setReturnMessage('danger', 'Veuillez corriger les erreurs');
      $_SESSION['author'] = $author;
    }else{
      if(!empty($pass) && !empty($pass2) && $pass === $pass2) {
        $pass = password_hash($pass , PASSWORD_DEFAULT);
        $author->setPass($pass);
      }
      $executeResult = $authorManager->setAuthor($author);
      if ($executeResult === false) {
        $this->setReturnMessage('danger', 'Impossible de modifier l\'autheur !');
      }
      else {
        $this->setReturnMessage('success', 'Auteur mis à jour avec succès');
      }
    }

    $this->editAuthor();
  }

  function reportedComments()
  {
    $commentManager = new CommentManager();
    $commentManager->countComments('reported');
    $commentsNumber = $commentManager->countItems();
    return $commentsNumber;
  }
}