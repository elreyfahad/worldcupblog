<?php $title = 'Réglages d\'auteur'; ?>
<?php $h1 = $title; ?>

<?php ob_start(); ?>

<form method="post" action="admin.php?action=author">
  <p>
    <label for="author">Nom d'auteur</label>
    <input type="text" name="author" class="form-control" value="<?= $author->name() ?>">
    <?php
    if($error = $this->formError('author')){
      echo $error;
    }
    ?>
  </p>
  <p>
    <label for="about_author">A propos de l'auteur</label>
    <textarea type="text" name="about_author" class="form-control" rows="3"><?= $author->about() ?></textarea>
    <?php
    if($error = $this->formError('about_author')){
      echo $error;
    }
    ?>
  </p>
  <p>
    <label for="author_pseudo">Pseudo d'auteur</label>
    <input type="text" name="author_pseudo" class="form-control" value="<?= $author->pseudo() ?>">
    <?php
    if($error = $this->formError('author_pseudo')){
      echo $error;
    }
    ?>
  </p>
  <p>
    <label for="email">E-mail</label>
    <input type="email" name="email" class="form-control" value="<?= $author->email() ?>">
    <?php
    if($error = $this->formError('email')){
      echo $error;
    }
    ?>
  </p>
  <p>
    <label for="pass">Mot de passe</label>
    <input type="password" name="pass" class="form-control">
    <?php
    if($error = $this->formError('pass')){
      echo $error;
    }
    ?>
  </p>
  <p>
    <label for="pass2">Confirmer le mot de passe</label>
    <input type="password" name="pass2" class="form-control">
  </p>
  <input type="hidden" name="submit" value="author">
  <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
