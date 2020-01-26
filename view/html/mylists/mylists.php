<div class="title">
  <hr>
   <h1>Espace de gestion des listes de voeux</h1></i>
  <hr>
</div>

<div class="col-lg-4">
  <div class="form-group">
   <fieldset>   
    <h1> Selectionnez une liste </h1> 
    <select class="form-control myoptions" id="controlselect">
      <?php $sSearch = ($_GET['p'] ? $_GET['p'] : "");      
        foreach( \controller\mylists\mylists::getLists('',$sSearch) as $post): ?>
        <option value=<?= $post->id ?> ><?= $post->name ?>&nbsp<strong>(<?= $post->firstname ?>)</strong></option>
      <?php endforeach; ?>
    </select>
    <hr>
    <div class="export">
      <a href="#"><i class='fas fa-file-export'></i>Exporter en PDF</a>
    </div>
    </fieldset>
  </div>
  <hr>
  <div class="form-group">
    <form role="form" method="post" action="">
      <fieldset>                 
        <h1> Créer une liste </h1> 
        <p class="lead"> Ajouter une nouvelle liste</p>   
        <div class="form-group">
          <input type="text" name="listname" class="form-control input-lg" placeholder="Nom de ma nouvelle liste">
        </div>
        <div>
          <input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Ajouter">
          <input id="resetbtn" type="button" class="btn btn-outline-success my-2 my-sm-0" value="Effacer">
        </div>
      </fieldset>
    </form>
  </div>
  <hr>
  <div class="form-group">
    <form role="form" method="post" action="">
      <fieldset>
        <h1> Supprimer une liste </h1> 
        <p class="lead"> Seulement pour vos propres listes</p> 
        <div>
          <input id="deleteList" type="button" class="btn btn-outline-success my-2 my-sm-0" value="Supprimer la liste actuellement selectionnée">
        </div>
      </fieldset>
    </form>
  </div>
</div>

<div class="col-lg-8">
    <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th class="lead" scope="col" hidden>id_item</th>
              <th class="lead" scope="col" hidden>id_list</th>
              <th class="lead" scope="col"><strong>Aperçu</strong></th>
              <th class="lead" scope="col"><strong>Nom</strong></th>
              <th class="lead" scope="col"><strong>Réservation</strong></th>
              <th class="lead" scope="col"><strong>Action</strong></th>
            </tr>
          </thead>
          <!--- Contenu de mon tableau liste qui sera rempli dans mylists.js --->
          <tbody>
          </tbody>
          <!------------------------------>
        </table>
    </div>
<div class="response"></div>
<div class="progress">
  <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Chargement en cours...</div>
</div>
</div>

<div class="modal"> 
  <form class="modalform" role="form" method="post" action="">
    <fieldset>             
      <h1> Ajouter de nouveaux objets à une liste </h1>
      </br> 
      <div class="form-group">
        <input type="text" name="itemname" class="form-control input-lg" placeholder="Nom de mon nouvel objet">
      </div>
      <div class="form-group"> 
        <select class="form-control" name="add_select">
          <?php foreach( \controller\mylists\mylists::getLists() as $post): ?>
            <?php if($_SESSION['id_user'] == $post->id_user){ ?>
              <option value=<?= $post->id ?> ><?= $post->name ?>&nbsp<strong>(<?= $post->firstname ?>)</strong></option>
             <?php } ?>
          <?php endforeach; ?>
        </select>         
      </div>
      <label for="basic-url">Lien vers l'article a acheter</label>
      <div class="input-group mb-3">
        <div class="input-group-append">
          <span class="input-group-text" id="basic-addon3">url</span>
        </div>
        <input type="text" name="linkurl" class="form-control" aria-describedby="basic-addon3">
      </div>
      <div class="form-group">
        <label for="description_newitem">Description</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
      </div>
      <div class="form-group">
          <label for="form-control-file1">Photo de l'objet</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
          <input type="file" id="addimg_upload" class="form-control-file">
      </div>  
    </fieldset>
  </form>
</div>
<a rel="modal:open" id="addItem" alt="addItem" title="Ajouter un article"><i class="fas fa-plus"></i></a>


