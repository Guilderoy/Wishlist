<div class="col-lg-12">
  <div class="jumbotron">
    <h1 class="display-4">Joyeux Noel à tous !</h1>
    <p class="lead">C'est ici que vous trouverez tous les cadeaux dont aurez besoin pour faire plaisir à vos proches !</br>
        N'hésitez pas à créer un compte juste <a href="/myaccount">ici</a> pour utiliser toutes les fonctionnalités du site web.
    </p>
    <p class="lead"> Vous pourrez créer,gérer vos listes de Noel et même les exporter pour les emmener avec vous et ne rien oublier ! </p>
    <hr class="my-4">
  </div>
 
  <hr>
   <h1>Articles récemment ajoutés</h1></i>
  <hr>

  <div class="row">
    <?php foreach ( \controller\items\items::getItems() as $post):?>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
          <a href="#"><img class="card-img-top" src=<?= $post->img ?> alt=""></a>
      
          <div class="card-body">
            <h4 class="card-title"><a href=<?= $post->url ?> target="_blank" name="item_name"><?= $post->name ?></a></h4>
            <p class="card-text"><?= $post->description ?></p>
          </div>
          <div class="card-footer">
              <a class="btn btn-success" href="#" name="item_ident" id=<?=$post->id?> > <i class="fas fa-heart"></i></a>
          </div>
        </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div id="rowappend" class="row"></div>

<div class="modal"> 
  <form class="modalform" role="form" method="post" action="">
    <fieldset>             
      <h1> Ajouter l'objet à une de mes listes</h1>
      </br> 
      <label>Selectionnez la liste</label> 
      <div class="form-group">  
        <select class="form-control" name="add_select">
           <?php foreach( \controller\mylists\mylists::getLists() as $post): ?>
            <?php if($_SESSION['id_user'] == $post->id_user){ ?>
              <option value=<?= $post->id ?> ><?= $post->name ?>&nbsp<strong>(<?= $post->firstname ?>)</strong></option>
             <?php } ?>
          <?php endforeach; ?>
        </select>         
      </div>
      <div class='alert alert-info' role='alert'>Veillez à bien vous connecter avant de tenter d'ajouter un objet à une liste</div>
    </fieldset>
  </form>
</div>
<div class="response"></div>
<div class="progress" hidden>
  <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Chargement en cours...</div>
</div>

 <a href="javascript:" id="return-to-top"><i class="fas fa-chevron-circle-up"></i></a>
</div>
