<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title><?= $title ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="admin/css/style.css" rel="stylesheet" />
  <script src="admin/js/admin.js"></script>
  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, author-scalable=0'>
</head>
<body>
<div id="container" class="container-fluid">
  <div class="row">
    <div id="admin-nav" class="col-md-2">
      <div class="list-group">
        <a class="list-group-item active">Tableau de bord</a>
        <a class="list-group-item" href="index.php"><i class="glyphicon glyphicon-home"></i> Afficher le blog</a>
        <a class="list-group-item" href="admin.php"><i class="glyphicon glyphicon-list"></i> Articles</a>
        <a class="list-group-item" href="admin.php?action=newPost"><i class="glyphicon glyphicon-file"></i> Nouvel article</a>
        <a class="list-group-item" href="admin.php?action=editComments"><i class="glyphicon glyphicon-comment"></i> Commentaires</a>
        <a class="list-group-item <?php if(!$this->reportedComments()){ echo 'disabled';}else{echo '" href="admin.php?action=editComments&reported=1';} ?>"><i class="glyphicon glyphicon-comment"></i> Signalisations <?php if($this->reportedComments()){ echo '<span class="badge">' . $this->reportedComments() . '</span>';} ?></a>
        <a class="list-group-item" href="admin.php?action=author"><i class="glyphicon glyphicon-user"></i> Auteur</a>
        <a class="list-group-item" href="admin.php?action=settings"><i class="glyphicon glyphicon-cog"></i> Réglages</a>
        <a class="list-group-item" href="index.php?action=logout"><i class="glyphicon glyphicon-log-out"></i> Se déconnecter</a>
      </div>
    </div>
    <div id="admin-area" class="col-md-10">
      <h1><?= $h1 ?></h1>
      <?php if($returnMessage = $this->returnMessage()){?>
        <p class="alert alert-<?= $returnMessage['type'] ?>"><?= $returnMessage['message'] ?></p>
      <?php } ?>
      <?= $content ?>
    </div>
  </div>
</div>
</body>
</html>