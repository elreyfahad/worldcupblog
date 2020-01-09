<aside id="sidebar">
  <div id="about_author" class="panel panel-primary">
    <div class="panel-heading">
      A propos de l'auteur
      <?php if($this->loggedIn()){ ?>
        <a class="btn btn-xs btn-default" href="admin.php?action=author">Modifier</a>
      <?php } ?>
    </div>
    <div class="panel-body">
      <p>
        <?= $this->getAuthor()->about() ?>
      </p>
    </div>
  </div>

  <?php if($this->getRecentPosts()){ ?>
    <div class="panel panel-primary">
      <div class="panel-heading">Articles récents</div>
      <div class="panel-body">
        <ul>
          <?php foreach($this->getRecentPosts() as $post){ ?>
            <li><a href="<?= $post->link() ?>"><?= $post->title() ?></a> <small>Le: <?= $post->dateFr() ?></small></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  <?php } ?>

  <?php if($this->getRecentComments()){ ?>
    <div class="panel panel-primary">
      <div class="panel-heading">Commentaires récents</div>
      <div class="panel-body">
          <?php foreach($this->getRecentComments() as $comment){ ?>
            <div class="well well-sm"><h4 class="media-heading"><?= $comment->author() ?> <a href="<?= $comment->link() ?>"><small><?= $comment->dateFr() ?></small></a></h4><?= $comment->content() ?></div>
          <?php } ?>
      </div>
    </div>
  <?php } ?>
</aside>