<?php
  $page_title = 'Add Group';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   //page_require_level(1);
?>
<?php
  if(isset($_POST['add'])){

   $req_fields = array('departament-titol','nom-dispositiu', 'nom-persona', 'cognom-persona');
   validate_fields($req_fields);

   if(empty($errors)){
        $d_titol  = remove_junk($db->escape($_POST['departament-titol']));
        $n_dispo  = remove_junk($db->escape($_POST['nom-dispositiu']));
        $n_pers = remove_junk($db->escape($_POST['nom-persona']));
        $cogn_pers  = remove_junk($db->escape($_POST['cognom-persona']));

        $query  = "INSERT INTO departaments (";
        $query .="departament, dispositiu, nom, cognom";
        $query .=") VALUES (";
        $query .=" '{$d_titol}', '{$n_dispo}','{$n_pers}','{$cogn_pers}' ";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"Has afegit un departament amb un dispositiu i la persona que el té! ");
          redirect('add_departament.php', false);
        } else {
          //failed
          $session->msg('d',"No s'ha pogut afegir nigun departament amb les seues dades.");
          redirect('add_departament.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_departament',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Afegix Departament</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="add_departament.php" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Departament</label>
              <input type="name" class="form-control" name="departament-titol">
        </div>
        <div class="form-group">
              <label for="name" class="control-label">Dispositiu</label>
              <input type="name" class="form-control" name="nom-dispositiu">
        </div>
        <div class="form-group">
              <label for="name" class="control-label">Nom</label>
              <input type="name" class="form-control" name="nom-persona">
        </div>
        <div class="form-group">
              <label for="name" class="control-label">Cognom</label>
              <input type="name" class="form-control" name="cognom-persona">
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="add" class="btn btn-info">Crea</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
