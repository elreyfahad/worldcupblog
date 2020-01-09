<?php
require_once('Controller/Controller.php');

class Frontend extends Controller
{
  function post($postId)
  {
    $blog = $this->getBlog();

    $postManager = new PostManager('front');
    $post = $postManager->getPost($postId);
    if($post){
      $author = $this->getAuthor();

      if (isset($_SESSION['comment'])){
        $comment = $_SESSION['comment'];
        unset($_SESSION['comment']);
      }

      $commentManager = new CommentManager('front');

      $comments = $commentManager->getComments($postId);

      $pagination = $commentManager->pagination("index.php?action=post&id=$postId&");

      require_once('view/frontend/post.php');
    } else {
      throw new Exception('Identifiant d\'article introuvable');
    }
  }

  function listPosts()
  {
    $blog = $this->getBlog();
    $title = $blog->title();

    $postManager = new PostManager('front');
    $posts = $postManager->getPosts();

    $pagination = $postManager->pagination("index.php?");

    $author = $this->getAuthor();

    require_once('view/frontend/home.php');
  }

  function addComment($postId, $author, $content)
  {
    $commentManager = new CommentManager('front');
    $postMananger = new PostManager('front');
    $post = $postMananger->getPost($postId);
    $commentOrder = $post->commentsNumber()+1;

    $comment = new Comment(['postId'=>$postId, 'commentOrder'=>$commentOrder, 'author'=>$author, 'content'=>$content]);

    $this->setRedirection('index.php?action=post&id=' . $postId . '#add_comment');

    if(!ctype_alnum(str_replace(' ','',$author))){
      $this->setFormError('author','Vous avez entré un nom incorrect');
    }

    if(empty($content)){
      $this->setFormError('comment','Le champ de commentaire est obligatoire');
    }

    if($this->formError){
      $this->setReturnMessage('danger', 'Veuillez corriger les erreurs');
      $_SESSION['comment'] = $comment;
    } else {
      $lastInsertId = $commentManager->newComment($comment);
      if($lastInsertId === '0'){
        $this->setReturnMessage('danger', 'Impossible d\'ajouter le commentaire !');
      } else {
        $postManager = new PostManager('front');
        $postManager->addComment($postId);
        $comment = $commentManager->getComment($lastInsertId);
        $this->setReturnMessage('success', 'Votre commentaire est publié');
        $this->setRedirection($comment->link());
      }
    }
  }

  function reportComment($commentId)
  {
    $commentManager = new CommentManager('front');
    $comment = $commentManager->getComment($commentId);
    if ($comment){
      if ($comment->reported()) {
        throw new Exception('Vous avez déja signalé ce commentaire !');
      } else {
        $executeResult = $commentManager->reportComment($comment);
        if($executeResult === false){
          throw new Exception('Impossible de signaler le commentaire !');
        } else {
          $_SESSION["reportComment-".$comment->id()] = "reported";
          $this->setRedirection('index.php?action=post&id='.$comment->postId());
        }
      }
    } else {
      throw new Exception('Identifiant de commentaire introuvable');
    }
  }

  function login($pseudo, $pass, $redirectTo)
  {
    $pseudo = strtolower($pseudo);
    $author = $this->getAuthor();
    $error = false;

    if ($pseudo === $author->pseudo()){
      //$pv = password_verify($pass, $author->pass());
        $pv = hash_equals($pass, $author->pass());

      if($pv){
        $_SESSION['id'] = $author->id();
        $_SESSION['pseudo'] = $pseudo;
        $this->setRedirection(urldecode($redirectTo));
      }else{
        $this->setReturnMessage('danger', 'Mot de passe erroné !');
        $error = true;
      }
    }else{
      $this->setReturnMessage('danger', 'Utilisateur non trouvé !');
      $error = true;
    }
    if($error){
      $this->getLoginForm();
    }
  }

  function getAuthor()
  {
    $authorManager = new AuthorManager();
    $author = $authorManager->getAuthor();
    return $author;
  }

  function getRecentComments()
  {
    $commentManager = new CommentManager('front');
    $recentComments = $commentManager->getComments('all', 1, 5);
    return $recentComments;
  }

  function getRecentPosts()
  {
    $postManager = new PostManager('front');
    $recentPosts = $postManager->getPosts(1, 5);
    return $recentPosts;
  }

  function getLoginForm()
  {
    require('view/frontend/login.php');
  }

  function logout()
  {
    session_destroy();
    $this->setRedirection('index.php');
  }
}