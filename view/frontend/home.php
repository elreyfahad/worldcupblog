<?php $title = $blog->title() ?>
<?php ob_start(); ?>
    <section>
    <?php if ($posts) {
      foreach($posts as $post) {?>
        <article class="post" id="post-<?= $post->id() ?>">

          <header>
            <h2><a href="<?= $post->link() ?>"><?= htmlspecialchars($post->title()) ?></a></h2>
            <p class="well well-sm">
              <span class="glyphicon glyphicon-user"></span> Par : <?= htmlspecialchars($author->name()) ?> | <span class="glyphicon glyphicon-calendar"></span> Le : <?= $post->dateFr() ?>
              <?php if($post->commentsNumber()){ ?>
              | <span class="glyphicon glyphicon-comment"></span> <a href="<?= $post->link() .'#comments' ?>">Commentaires</a> <span class="badge"><?= $post->commentsNumber() ?></span>
              <?php } ?>
              <?php if($this->loggedIn()){ ?>
                | <a class="btn btn-xs btn-primary" href="<?= $post->link('edit') ?>">Modifier</a>
                <a class="btn btn-xs btn-danger" href="<?= $post->link('delete') ?>&amp;redirect_to=home">Supprimer</a>
              <?php } ?>
            </p>
          </header>

          <div class="post-content"><?= $post->excerpt('Lire la suite', 'btn btn-default btn-sm') ?></div>
        </article>
        <hr>
    <?php }
      if ($pagination){ echo $pagination; }; ?>
    <?php } else { ?>
      <p>Aucun article publié</p>
    <?php } ?>
    </section>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>