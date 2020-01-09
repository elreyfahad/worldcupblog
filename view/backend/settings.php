<?php $title = 'Réglages du blog'; ?>
<?php $h1 = $title; ?>

<?php ob_start(); ?>

<form method="post" action="admin.php?action=settings">
  <p>
    <label for="title">Titre du blog</label>
    <input type="text" name="title" class="form-control" value="<?= $blog->title() ?>">
  </p>
  <p>
    <label for="description">Description du blog</label>
    <textarea type="text" name="description" class="form-control" rows="4"><?= $blog->description() ?></textarea>
  </p>

    <div class="col">
      <div class="row">
        <div class="col-sm-3">
          <p>Afficher <input name="ppp" type="number" min="1" max="99" class="form-control number" value="<?= $blog->itemsPerPage('ppp') ?>"> articles par page</p>
        </div>
        <div class="col-sm-3">
          <p>Afficher <input name="cpp" type="number" min="1" max="99" class="form-control number" value="<?= $blog->itemsPerPage('cpp') ?>"> commentaires par page</p>
        </div>
        <div class="col-sm-3">
          <p>Modérer <input name="pppa" type="number" min="1" max="99" class="form-control number" value="<?= $blog->itemsPerPage('pppa') ?>"> articles par page</p>
        </div>
        <div class="col-sm-3">
          <p>Modérer <input name="cppa" type="number" min="1" max="99" class="form-control number" value="<?= $blog->itemsPerPage('cppa') ?>"> commentaires par page</p>
        </div>
      </div>
    </div>
  <br>
    <input type="hidden" name="submit" value="blog">
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
