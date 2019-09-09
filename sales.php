<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  if(!$action) {
    isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  build_header($displayName);
?>
  <?PHP build_navBlock(); ?>
  <div id="content">
    <div class="PageTitle">Sales Management Search</div>

  <!-- Search Section Start -->
    <form name="frmSearchStock" method="post"
          id="frmSearchStock"
          action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div class="displayPane">
        <div class="displayPaneCaption">Search:</div>
        <div>
          <input name="txtSearch" id="txtSearch" placeholder="Search Sale"
          style="width: calc(100% - 115px)" value="<?PHP echo $txtSearch; ?>">
          <button type="button" id="btnSearch">Search</button>
          <button type="button" id="btnAddRec">Add</button>
        </div>
      </div>
      <input type="hidden" name="a" value="listStock">
    </form>
	<?PHP valid_user(); ?>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listStock") {
    ?>
    <div>
      <table style="width: calc(100% - 15px);border: #cccccc solid 1px">
        <tr style="background: #eeeeee">
          <td style="width: 80px;border-left: #cccccc solid 1px"><b>Sales number</b></td>
          <td style="border-left: #cccccc solid 1px"><b>Client</b></td>
          <td style="width: 80px;text-align: right"><b>Amount</b></td>
		  <td style="width: 80px;text-align: right"><b>Status</b></td>
        </tr>
    <?PHP
      openDB();
      $query =
        "SELECT
            *
         FROM
            lpa_invoices
         WHERE
            	lpa_inv_client_ID LIKE '%$txtSearch%' AND lpa_inv_status <> 'D'
         OR
            lpa_inv_client_name LIKE '%$txtSearch%' AND lpa_inv_status <> 'D'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_inv_no'];
          ?>
          <tr class="hl">
            <td onclick="loadStockItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
              <?PHP echo $sid; ?>
            </td>
            <td onclick="loadStockItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_inv_client_name']; ?>
            </td>
            <td style="text-align: right">
              <?PHP echo $row['lpa_inv_amount']; ?>
            </td>
			<td onclick="loadStockItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_inv_status']; ?>
            </td>
			 <?PHP $total += $row['lpa_inv_amount']; ?>
          </tr>
        <?PHP }
      } else { ?>
        <tr>
          <td colspan="3" style="text-align: center">
            No Records Found for: <b><?PHP echo $txtSearch; ?></b>
          </td>
        </tr>
      <?PHP } ?>
	  <tr class="hl">
		  <td style="text-align: right">
              <?PHP echo "-"; ?>
            </td>
		  <td style="text-align: right">
              <?PHP echo "Total"; ?>
            </td>
		  <td style="text-align: right">
              <?PHP echo $total; ?>
            </td>
			<td style="text-align: right">
              <?PHP echo "-"; ?>
          </td>
		  </tr>
      </table>
    </div>
    <?PHP } ?>
    <!-- Search Section List End -->
  </div>
  <script>
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
    if(action == "recUpdate") {
      alert("Record Updated!");
      navMan("sales.php?a=listStock&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("sales.php?a=listStock&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("sales.php?a=listStock&txtSearch=" + search);
    }
    function loadStockItem(ID,MODE) {
      window.location = "salesaddedit.php?sid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchStock").submit();
    });
    $("#btnAddRec").click(function() {
      loadStockItem("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
<?PHP
build_footer();
?>