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
//Buscador
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (!empty($search)) {
  $data = search_departaments($search, $limit, $offset);
  $all_departaments = $data['records'];
  $total_records = $data['total'];
} else {
  $all_departaments = find_all_departament();
  $total_records = count($all_departaments);
}

$total_pages = ($limit > 0) ? ceil($total_records / $limit) : 1;

?>

<?php
function search_departaments($search, $limit = null, $offset = null)
{
  global $db;
  $sql = "SELECT * FROM departaments ";
  $sql .= "WHERE departament LIKE '%{$search}%' ";
  $sql .= "OR dispositiu LIKE '%{$search}%' ";
  $sql .= "OR cognom LIKE '%{$search}%' ";

  if ($limit !== null) {
    $sql .= " LIMIT {$limit} OFFSET {$offset}";
  }

  return find_by_sql($sql);

  $count_sql = "SELECT COUNT(*) as total FROM departaments ";
  $count_sql .= "WHERE departament LIKE '%{$search}%' ";
  $count_sql .= "OR dispositiu LIKE '%{$search}%' ";
  $count_sql .= "OR cognom LIKE '%{$search}%' ";

  $count_result = find_by_sql($count_sql);
  $total_records = $count_result[0]['total'];

  return ['records' => $results, 'total' => $total_records];
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

<?php include_once('layouts/footer.php'); ?>