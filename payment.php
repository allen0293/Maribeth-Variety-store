<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/swal.js"></script>
    <title>MARIBETH VARIETY STORE</title>
  </head>
  <body>
    <?php
      error_reporting(0);
      require_once "classes/variables.php";
      include("includes/security.php");
      include("includes/navbar.php");
      include("includes/sidebar.php");
      require_once "classes/productClass.php";
      $pro =  new Product();
      $var = new Variables();
      $_SESSION['redirect']=$_SERVER['PHP_SELF'];  
      $stmt = $pro->runQuery("SELECT * FROM pos WHERE status='processing' ");
      $stmt->execute();
      $count  = 0;
      $count = $stmt->rowCount();
      if($count == 0){
        header('Location: POS.php');  
      }
   ?>
    <main class="mt-5 pt-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h4 class="text-center">PAYMENT</h4>
          </div>
        </div>
        <div class="d-flex  align-content-center justify-content-center ">
            <table
                class="table border"
                style="width: 50%">
                <tbody>
                    <?php 
                    $stmt = $pro->runQuery("SELECT pos.pos_id, inventory.invt_id, inventory.name, inventory.brand_name, pos.unit_price, pos.qnty, pos.total, pos.status 
                    FROM `pos` 
                    INNER JOIN inventory 
                    ON pos.invt_id = inventory.invt_id 
                    WHERE status = 'processing'");
                    $stmt->execute();
                    $arrayPos = array();
                    $total = 0;  
                    $count = $stmt->rowCount();
                    if($count > 0){
                        while($rowPos = $stmt->fetch(PDO::FETCH_ASSOC)){     
                        $arrayPos[] = $rowPos;
                    }}
                    foreach($arrayPos as $row){
                        $total = $total + $row['total'];
                    ?>
                    <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['brand_name']; ?></td>
                    <td><?php echo number_format($row['unit_price'],2); ?></td>
                    <td><?php echo $row['qnty']; ?></td>
                    <td><?php echo number_format($row['total'],2); ?></td>
                    </tr>
                    <?php } 
                    $change = $_SESSION['amount']-$total;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Total:</th>
                        <th><?php echo number_format($total,2); ?></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align:right">Tendered Cash:</th>
                        <th><?php echo number_format($_SESSION['amount'],2); ?></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align:right">Change:</th>
                        <th><?php echo number_format($change,2); ?></th>
                    </tr>
                </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-center">
              <form action=<?php echo htmlspecialchars("export.php");?> method="post">
                    <button name="receipt" class="d-flex align-self-center btn btn-sm btn btn-success " type="submit">Print</button>
                </form>
            </div>
           
      </div>
      </div>
    </main>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>

 </body>
</html>
<?php 
?>