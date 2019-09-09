<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_REQUEST['sid'])? $sid = $_REQUEST['sid'] : $sid = "";
  if(!$sid) {
    isset($_POST['sid'])? $sid = $_POST['sid'] : $sid = "";
  }
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  if($action == "delRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_status = 'D'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: sales.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtSaleDate'])? $txtSaleDate = $_POST['txtSaleDate'] : $txtSaleDate = "";
  isset($_POST['txtSaleClientID'])? $SaleClientID = $_POST['txtSaleClientID'] : $SaleClientID = gen_ID2();
  isset($_POST['txtSaleName'])? $SaleName = $_POST['txtSaleName'] : $SaleName = "";
  isset($_POST['txtSaleAddress'])? $SaleAddress = $_POST['txtSaleAddress'] : $SaleAddress = "";
  isset($_POST['txtSaleAmount'])? $SaleAmount = $_POST['txtSaleAmount'] : $SaleAmount = "0.00";
  isset($_POST['txtStatus'])? $stockStatus = $_POST['txtStatus'] : $stockStatus = "";
  $mode = "insertRec";
  if($action == "updateRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_date = '$txtSaleDate',
         lpa_inv_client_name = '$SaleClientID',
         lpa_inv_client_address	= '$SaleName',
         lpa_inv_client_ID= '$SaleAddress',
         lpa_inv_amount = '$SaleAmount',
         lpa_inv_status= '$stockStatus'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
         header("Location: sales.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {

  $newDate = date("Y-m-d", strtotime($txtSaleDate));

    $query =
      "INSERT INTO lpa_invoices (
				 lpa_inv_date,
				 lpa_inv_client_ID,
				 lpa_inv_client_name,
				 lpa_inv_client_address,
				 lpa_inv_amount,
				 lpa_inv_status
       ) VALUES (
         '$newDate',
         '$SaleClientID',
         '$SaleName',
         '$SaleAddress',
         '$SaleAmount',
         '$stockStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: sales.php?a=recInsert&txtSearch=".$SaleName);
      exit;
    }
  }

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_invoices WHERE lpa_inv_no = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $txtSaleDate     = $row['lpa_inv_date'];
    $SaleClientID   = $row['lpa_inv_client_ID'];
    $SaleName   = $row['lpa_inv_client_name'];
    $SaleAddress = $row['lpa_inv_client_address'];
    $SaleAmount  = $row['lpa_inv_amount'];
    $stockStatus = $row['lpa_inv_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="PageTitle">Sales Record Management (<?PHP echo $action; ?>)</div>
    <form name="frmStockRec" id="frmStockRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <input name="txtSaleDate" id="datepicker" placeholder="Date" style="width: 100px;" title="Sale Date" value="<?PHP echo $txtSaleDate; ?>">
      </div>
	  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  /*
  "#datepicker" ).datepicker();
  $("$txtSaleDate").val($.datepicker.formatDate('yy M dd', new Date()));
  'window.alert(<?PHP txtSaleDate ?>);
  'window.alert(date);*/
  </script>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtSaleClientID" id="txtSaleClientID" placeholder="Client ID" value="<?PHP echo $SaleClientID; ?>" style="width: 400px;"  title="Client ID">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <textarea name="txtSaleName" id="txtSaleName" placeholder="Client name" style="width: 400px;"  title="Client Name"><?PHP echo $SaleName; ?></textarea>
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtSaleAddress" id="txtSaleAddress" placeholder="Client Address" value="<?PHP echo $SaleAddress; ?>" style="width: 400px;text-align: right"  title="Client Address">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtSaleAmount" id="txtSaleAmount" placeholder="Sale Amount" value="<?PHP echo $SaleAmount; ?>" style="width: 90px;text-align: right"  title="Sale Amount" >
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>Sale Status:</div>
        <input name="txtStatus" id="txtStockStatusActive" type="radio" value="P">
          <label for="txtStockStatusActive">Paid</label>
        <input name="txtStatus" id="txtStockStatusInactive" type="radio" value="U">
          <label for="txtStockStatusInactive">UnPaid</label>
      </div>
      <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
      <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" id="btnStockSave">Save</button>
      <button type="button" onclick="navMan('sales.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
  <script>
    var stockRecStatus = "<?PHP echo $stockStatus; ?>";
    if(stockRecStatus == "a") {
      $('#txtStockStatusActive').prop('checked', true);
    } else {
      $('#txtStockStatusInactive').prop('checked', true);
    }
    $("#btnStockSave").click(function(){
        $("#frmStockRec").submit();
    });
    function delRec(ID) {
      navMan("salesaddedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtSaleClientID").focus();
    },1);
  </script>
<?PHP
build_footer();
?>