<?php
$title = 'Se connecter';
require('header.php');
?>
  <div class="panel panel-primary" id="login">
    <div class="panel-heading">Se connecter</div>
    <div class="panel-body">
      <?php if($returnMessage = $this->returnMessage()){?>
        <p class="alert alert-<?= $returnMessage['type'] ?>"><?= $returnMessage['message'] ?></p>
      <?php } ?>
      <form method="post" action="index.php?action=login">
        <?php if (isset($_GET['redirect_to'])){ ?>
          <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_GET['redirect_to']) ?>"></input>
        <?php } ?>
        <p>
          <label for="pseudo">Pseudo:</label><br />
          <input type="text" class="form-control" name="pseudo" placeholder="Pseudo">
        </p>
        <p>
          <label for="pass">Mot de passe:</label><br />
          <input type="password" class="form-control" name="pass" class="form-control" placeholder="Mot de passe">
        </p>
        <button type="submit" class="btn btn-primary">Se connecter</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>