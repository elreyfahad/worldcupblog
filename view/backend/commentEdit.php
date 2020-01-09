<?php $title = 'Modifier le commentaire'; ?>
<?php $h1 = $title; ?>

<?php ob_start(); ?>
<form method="post" action="admin.php?action=editComment&id=<?=$comment->id(); ?>">
  <p>
    Auteur:
    <input type="text" name="author" class="form-control" value="<?= $comment->author(); ?>"><br />
  </p>
  <p>
    Commentaire:
    <textarea name="comment" rows="8" class="form-control"><?= $comment->content(); ?></textarea><br />
  </p>
  <input type="hidden" name="submit" value="comment">
  <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
