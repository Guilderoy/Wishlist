<div class="jumbotron">
  <h1 class="display-12"><img class = "avatarreg" src="medias\logos\iconaccount.png" alt="">Espace Administration</h1>
  <p class="lead"> Vous pouvez modifier les informations concernant votre compte dans cet espace.</p>
  <p class="lead"> Invitez également vos proches pour qu'ils puissent profiter du site. Ils pourront consulter vos listes, en créer de nouvelles</p> 
  <hr class="my-12">
</div>

<div class="col-lg-6">
  <form role="form" method="post" action="">
    <fieldset>
      <p class="lead"> Vous souhaitez modifier votre compte ? </p>              
      <h1> Par ici </h1> 
      <?php foreach (\models\login\login::getAllUser($_SESSION) as $post):?>
      <div class="form-group">
        <input type="text" name="firstname" class="form-control input-lg"  value=<?= $post->firstname ?> placeholder="Nom de famille">
      </div>

      <div class="form-group">
        <input type="text" name="lastname" class="form-control input-lg" value=<?= $post->lastname ?> placeholder="Prénom">
      </div>

      <div class="form-group">
        <input type="text" name="username" class="form-control input-lg" readonly="true" value=<?= $post->username ?> placeholder="Nom d'utilisateur">
      </div>

      <div class="form-group">
        <input type="email" name="email" id="email" class="form-control input-lg" value=<?= $post->email ?> placeholder="Adresse e-mail">
      </div>
      <div class="form-group">
        <input type="password" name="password" id="password_reg" class="form-control input-lg" placeholder="Entrez un mot de passe">
      </div>
        <div class="form-group">
        <input type="password" name="passwordconfirm" id="password_regconfirm" class="form-control input-lg" placeholder="Retapez le mot de passe">
      </div>
      <?php endforeach;?>
      <div>
        <input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Modifier mon compte">
        <input id="resetbtn" type="button" class="btn btn-outline-success my-2 my-sm-0" value="Effacer">
      </div>
    </fieldset>
  </form>
</div>

<div class="col-lg-6">
  <form role="form" method="post">
    <fieldset>
      <p class="lead"> Invitez de la famille ou vos amis les plus proches ! </p>              
      <h1> Envoyez lui un email : </h1> 
      <div class="form-group">
        <input type="email" name="email_invit" class="form-control input-lg" placeholder="Adresse e-mail">
      </div>
     <div class="form-group">
        <button id="sendinvit" type="button" class="btn btn-success"> Envoyer l'invitation  </button>
      </div> 
    </fieldset>
  </form>
</div>

<div class="progress">
  <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Chargement en cours...</div>
</div>
<div class="response"></div>