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

<?php
//Paginador
$limit = 20;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$total_records = count(find_all_departament());

$total_pages = ceil($total_records / $limit);

$all_departaments = find_departaments_with_limit($limit, $offset);

?>

<?php
//Buscador
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
  $all_departaments = search_departaments($search, $limit, $offset);
  $total_records = count(search_departaments($search));
} else {
  $all_departaments = find_departaments_with_limit($limit, $offset);
  $total_records = count(find_all_departament());
}

$total_pages = ceil($total_records / $limit);
?>

<?php
function search_departaments($search, $limit = null, $offset = null)
{
  global $db;
  $sql = "SELECT * FROM departaments ";
  $sql .= "WHERE departament LIKE '%{$search}%' ";
  $sql .= "OR dispositiu LIKE '%{$search}%' ";
  $sql .= "OR cognom LIKE '%{$search}%' ";

  if ($limit !== null && $offset !== null) {
    $sql .= "LIMIT {$limit} OFFSET {$offset}";
  }

  return find_by_sql($sql);
}
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
        <form method="get" action="" class="pull-right">
        <a href="add_departament.php" class="btn btn-info pull-right">Afegir Departament</a>
          <input type="text" name="search" placeholder="Buscar..."
            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
          <input type="submit" value="Buscar" class="btn btn-success" />
        </form>
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
            <?php foreach ($all_departaments as $a_departament): ?>
              <tr>
                <td><?php echo remove_junk(ucwords($a_departament['departament'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_departament['dispositiu'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_departament['nom'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_departament['cognom'])) ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_departament.php?id=<?php echo (int) $a_departament['id']; ?>"
                      class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Paginador -->
<nav aria-label="Page navigation">
  <ul class="pagination">
    <?php if ($page > 1): ?>
      <li><a href="?page=<?php echo $page - 1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="<?php if ($i == $page)
        echo 'active'; ?>"><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
      <li><a href="?page=<?php echo $page + 1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
    <?php endif; ?>
  </ul>
</nav>
</div>

<?php include_once('layouts/footer.php'); ?>