<?php
  $page_title = 'Departaments';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 //page_require_level(1);
//pull out all user form database
 $all_departaments = find_all_departament();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Departaments</span>
       </strong>
         <a href="add_departament.php" class="btn btn-info pull-right">Afegir Departament</a>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">Departament</th>
            <th>Dispositiu</th>
            <th>Nom</th>
            <th>Cognom</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_departaments as $a_departament): ?>
          <tr>
           <td><?php echo remove_junk(ucwords($a_departament['departament']))?></td>
           <td><?php echo remove_junk(ucwords($a_departament['dispositiu']))?></td>
           <td><?php echo remove_junk(ucwords($a_departament['nom']))?></td>
           <td><?php echo remove_junk(ucwords($a_departament['cognom']))?></td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_departament.php?id=<?php echo (int)$a_departament['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
              </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
