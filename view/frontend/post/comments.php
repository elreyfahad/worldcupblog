<?php if($comments){ ?>
  <h3 id="comments">Commentaires</h3>
  <?php foreach($comments as $comment){ ?>
    <div class="panel panel-default" id="comment-<?= $comment->id() ?>">
      <div class="panel-heading">
        Par : <strong><?= htmlspecialchars($comment->author()) ?></strong> le : <a href="<?= $comment->link() ?>"><?= $comment->dateFr() ?></a>
        <?php if($this->loggedIn()){ ?>
          <a class="btn btn-xs btn-primary" href="admin.php?action=editComment&amp;id=<?= $comment->id() ?>">Modifier</a> <a class="btn btn-xs btn-danger" href="admin.php?action=deleteComment&amp;redirect_to=<?= $post->id() ?>&amp;id=<?= $comment->id() ?>">Supprimer</a>
        <?php } elseif ($comment->reported()) {?>
          <span class="label label-warning">Déjà Signalé</span><span class="badge"></span>
        <?php } else { ?>
          <a class="btn btn-xs btn-danger" href="index.php?action=reportComment&amp;id=<?= $comment->id() ?>"><span class="glyphicon glyphicon-alert"></span> Signaler inapproprié</a>
        <?php } ?>
      </div>
      <div class="panel-body">
        <p><?= htmlspecialchars($comment->content()) ?></p>
      </div>
    </div>
  <?php } ?>
  <?php if ($pagination){ echo $pagination; };
} else { ?>

<?php } ?>