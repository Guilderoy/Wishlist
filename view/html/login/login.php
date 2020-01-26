<!-- Login Form -->
<div class="col-lg-3">
  <article class="card-body">
    <img class = "avatar" src="medias\logos\iconlogin.png" alt="">
      <h1 class="card-title text-center mb-4 mt-1">Connectez vous</h1>
      <hr>
      <form>
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
         </div>
        <input name="usernamelog" class="form-control" placeholder="Utilisateur" type="email">
      </div>
      </div>
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
        </div>
      <input name="passwordlog" class="form-control" placeholder="******" type="password">
      </div>
      </div>
      <div class="form-group">
        <button id="connect" type="button" class="btn btn-success"> Se connecter  </button>
      </div>
      </form>
  </article>
</div>

<div class="col-lg-8">
  <img class="d-block img-fluid" src="medias/logos/banner.jpg" alt="First slide">
  <form role="form" method="post" action="">
    <fieldset>
      <p class="lead"> Pas encore de compte créé ? Par ici :</p>              
      <h1> Créer un compte </h1> 

      <div class="form-group">
        <input type="text" name="firstname" class="form-control input-lg" placeholder="Nom de famille">
      </div>

      <div class="form-group">
        <input type="text" name="lastname" class="form-control input-lg" placeholder="Prénom">
      </div>

      <div class="form-group">
        <input type="text" name="username" class="form-control input-lg" placeholder="Nom d'utilisateur">
      </div>

      <div class="form-group">
        <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Adresse e-mail">
      </div>
      <div class="form-group">
        <input type="password" name="password" id="password_reg" class="form-control input-lg" placeholder="Entrez un mot de passe">
      </div>
        <div class="form-group">
        <input type="password" name="passwordconfirm" id="password_regconfirm" class="form-control input-lg" placeholder="Retapez le mot de passe">
      </div>
      <div>
        <input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Créer votre compte">
        <input id="resetbtn" type="button" class="btn btn-outline-success my-2 my-sm-0" value="Effacer">
      </div>
    </fieldset>
  </form>
  <div class="response"></div>
  <div class="progress">
    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Chargement en cours...</div>
  </div>
</div>

