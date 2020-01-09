<?php $title = $blog->title() ?>
<?php $h1 = 'Modérer les articles'; ?>

<?php ob_start(); ?>
<?php if($posts){ ?>
<form method="post" action="admin.php?action=multipleDelete">
<input type="hidden" name="type" value="post"></input>
<p>
  <button type="submit" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer tout sélectionné</button>
  <input type="checkbox" onclick="toggle(this);" id="selectAll" name="selectAll"> <label for="selectAll">Sélectionner tout</label>
</p>
<?php foreach ($posts as $post) { ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <input type="checkbox" class="form-check-inline" name="multipleDelete[]" value="<?= $post->id() ?>"></input>
      Publié le <?= $post->dateFr() ?> Mis à jour le <?= $post->updateDateFr() ?> <a href="<?= $post->link() ?>"><span class="glyphicon glyphicon-eye-open"></span> Afficher</a>
    </div>
    <div class="panel-body"><?= htmlspecialchars($post->title()) ?></div>
    <div class="panel-footer">
      <a class="btn btn-sm btn-primary" href="<?= $post->link('edit') ?>"><span class="glyphicon glyphicon-edit"></span> Modifier</a>
      <a class="btn btn-sm btn-danger" href="<?= $post->link('delete') ?>"><span class="glyphicon glyphicon-trash"></span> Supprimer</a>
      <a class="btn btn-sm btn-default <?php if(!$post->commentsNumber()){ echo 'disabled';} ?>" href="<?= $post->link('editComments') ?>"><span class="glyphicon glyphicon-comment"></span> Commentaires <?php if($post->commentsNumber()){ echo '<span class="badge">' . $post->commentsNumber() . '</span>';} ?></a>
    </div>

  </div>
    
<?php } ?>
</form>
<p><button type="submit" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Supprimer tout sélectionné</button></p>
<?php } else { ?>
  <div class="panel panel-default panel-body">
    <p>Aucun article publié</p>
  </div>
<?php } ?>
<?php if ($pagination){ echo $pagination; }; ?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>