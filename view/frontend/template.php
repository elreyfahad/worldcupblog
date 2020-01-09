<?php require('header.php'); ?>
    <header class="page-header">
      <div class="header-background">
        <a href="index.php">
          <div class="blog-header">
            <h1><?= htmlspecialchars($blog->title()) ?></h1>
            <p><?= htmlspecialchars($blog->description()) ?></p>
          </div>
        </a>
      </div>
    </header>
    <div class="row">
      <div class="col-sm-8">
        <?= $content ?>
      </div>
      <div class="col-sm-4">
        <?php require('sidebar.php'); ?>
      </div>
    </div>
    <footer class="page-footer">
      <a class="btn" href="admin.php"><span class="glyphicon glyphicon-dashboard"></span> Tableau de bord</a>
      <?php if($this->loggedIn()){ ?>
        | <a class="btn" href="index.php?action=logout"><span class="glyphicon glyphicon-log-out"></span> Se déconnecter</a></li>
      <?php } ?>
    </footer>
  </div>
</body>
</html>