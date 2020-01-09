<?php $title = 'Modérer les commentaires'; ?>

<?php ob_start(); ?>
<?php if($comments){ ?>
<form method="post" action="admin.php?action=multipleDelete&amp;redirect_to=<?= $redirectTo ?>">
<input type="hidden" name="type" value="comment"></input>
<p>
  <button type="submit" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer tout sélectionné</button>
  <input type="checkbox" onclick="toggle(this);" id="selectAll" name="selectAll"> <label for="selectAll">Sélectionner tout</label>
</p>
<?php foreach ($comments as $comment) {?>
  <div class="panel panel-default <?php if($comment->reports()){?><?= 'panel-danger'; } ?>">
    <div class="panel-heading">
      <input type="checkbox" class="form-check-inline" name="multipleDelete[]" value="<?= $comment->id() ?>"></input>
      Par : <?= htmlspecialchars($comment->author()) ?>
      le : <a href="<?= $comment->link() ?>"><?= $comment->dateFr() ?></a>
      <?php if($comment->reports()){?><?= '<span class="label label-danger"><span class="glyphicon glyphicon-alert"></span> '.$comment->reports().'</span>'; } ?>
    </div>
    <div class="panel-body">
      <p><?= htmlspecialchars($comment->content()) ?></p>
    </div>
    <div class="panel-footer">
      <a class="btn btn-sm btn-primary" href="admin.php?action=editComment&amp;id=<?= $comment->id() ?>"><span class="glyphicon glyphicon-edit"></span> Modifier</a>
      <a class="btn btn-sm btn-danger" href="admin.php?action=deleteComment&amp;redirect_to=<?= $redirectTo ?>&amp;id=<?= $comment->id() ?>"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
      <a class="btn btn-sm btn-default" href="index.php?action=post&amp;id=<?= $comment->postId() ?>"><span class="glyphicon glyphicon-align-left"></span> Article</a>
    </div>
  </div>
  <?php } ?>
  <p>
    <button type="submit" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer tout sélectionné</button>
  </p>
</form>
<?php } else {?>
  <div class="panel panel-default panel-body">
    <p>Pas de commentaires à modérer</p>
  </div>
<?php } ?>
<?php
if ($pagination){ echo $pagination; };

$content = ob_get_clean();

 require('template.php'); ?>