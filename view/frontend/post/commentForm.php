<h3 id="add_comment">Ajouter un commentaire</h3>
<form class="well" action="index.php?action=addComment&amp;id=<?= $post->id() ?>" method="post">
    <?php if($returnMessage = $this->returnMessage()){?>
    <p class="alert alert-<?= $returnMessage['type'] ?>"><?= $returnMessage['message'] ?></p>
    <?php } ?>
    <p>
      <label for="author">Votre nom :</label><br />
      <input type="text" id="author" name="author" class="form-control" <?php if(isset($comment)){echo 'value="'.$comment->author().'"';} ?> />
      <?php
      if($error = $this->formError('author')){
        echo $error;
      }
      ?>
    </p>
    <p>
      <label for="comment">Commentaire :</label><br />
      <textarea id="comment" name="comment" rows="8" class="form-control"><?php if(isset($comment)){echo $comment->content();}?></textarea>
      <?php
      if($error = $this->formError('comment')){
        echo $error;
      }
      ?>
    </p>
    <p>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </p>
</form>