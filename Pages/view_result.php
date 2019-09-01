<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 01/09/2019
 * Time: 10:24 PM
 */
session_start();
require('db.php');

if (empty($_SESSION['id'])) {
    header("location:access-denied.php");
}

$content = '';
$sql = "SELECT DISTINCT category FROM election_table";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
        $sn = 1;
        $category = $row["category"];
        $content .= '<div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Results for ' . $category . '</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Votes</th>
                  </tr>
                </thead>';

        $sql = "SELECT * FROM election_table WHERE category='$category' ORDER BY vote_count";
        $result2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        while ($row = $result2->fetch_assoc()) {
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $vote_count = $row['vote_count'];
            if ($vote_count==null){$vote_count=0;}

            $content .= '<tbody>
                    <tr>
                    <td>' . $sn . '</td>
                    <td>' . $firstname . '</td>
                    <td>' . $lastname . '</td>
                    <td>' . $vote_count . '</td>
                  </tr>
                </tbody>';

        }
        $content .= ' </table>
            </div>
          </div>
        </div>';
        $sn+=1;
    }
}


$title = "Result page";
require_once("layout.php");
echo '<script>
$(document).ready(function() {
    $("table.dataTable").DataTable();
} );
</script>'
?>