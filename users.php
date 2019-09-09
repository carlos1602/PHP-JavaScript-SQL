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
    <div class="PageTitle">Users Management Search</div>

  <!-- Search Section Start -->
    <form name="frmSearchusers" method="post"
          id="frmSearchusers"
          action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div class="displayPane">
        <div class="displayPaneCaption">Search:</div>
        <div>
          <input name="txtSearch" id="txtSearch" placeholder="Search user"
          style="width: calc(100% - 115px)" value="<?PHP echo $txtSearch; ?>">
          <button type="button" id="btnSearch">Search</button>
          <button type="button" id="btnAddRec">Add</button>
        </div>
      </div>
      <input type="hidden" name="a" value="listusers">
    </form>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listusers") {
    ?>
    <div>
      <table style="width: calc(100% - 15px);border: #cccccc solid 1px">
        <tr style="background: #eeeeee">
          <td style="width: 80px;border-left: #cccccc solid 1px"><b>ID</b></td>
          <td style="border-left: #cccccc solid 1px"><b>User Name</b></td>
		  <td style="border-left: #cccccc solid 1px"><b>First Name</b></td>
		  <td style="border-left: #cccccc solid 1px"><b>Last Name</b></td>
		  <td style="border-left: #cccccc solid 1px"><b>Status</b></td>
          <td style="width: 80px;text-align: right"><b>Group</b></td>
        </tr>
    <?PHP
      openDB();
      $query =
        "SELECT
            *
         FROM
            lpa_users
         WHERE
            		lpa_user_username LIKE '%$txtSearch%' AND lpa_user_status <> 'D'
         OR
            	lpa_user_firstname LIKE '%$txtSearch%' AND lpa_user_status <> 'D'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_user_ID'];
          ?>
          <tr class="hl">
            <td onclick="loadusersItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
              <?PHP echo $sid; ?>
            </td>
            <td onclick="loadusersItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_user_username']; ?>
            </td>
			<td onclick="loadusersItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_user_firstname']; ?>
            </td>
			<td onclick="loadusersItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_user_lastname']; ?>
            </td>
			<td onclick="loadusersItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_user_status']; ?>
            </td>
            <td style="text-align: right">
              <?PHP echo $row['lpa_user_group']; ?>
            </td>
          </tr>
        <?PHP }
      } else { ?>
        <tr>
          <td colspan="3" style="text-align: center">
            No Records Found for: <b><?PHP echo $txtSearch; ?></b>
          </td>
        </tr>
      <?PHP } ?>
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
      navMan("users.php?a=listusers&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("users.php?a=listusers&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("users.php?a=listusers&txtSearch=" + search);
    }
    function loadusersItem(ID,MODE) {
		console.log("estoy en loadusersItem")
      window.location = "useraddedit.php?sid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchusers").submit();
    });
    $("#btnAddRec").click(function() {
      loadusersItem("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
<?PHP
build_footer();
?>