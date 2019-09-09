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
      "UPDATE lpa_users SET
         lpa_user_status = 'D'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: users.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtUserName'])? $txtUserName = $_POST['txtUserName'] : $txtUserName = gen_ID2();
  isset($_POST['txtPassword'])? $txtPassword = $_POST['txtPassword'] : $txtPassword = "";
  isset($_POST['txtFName'])? $txtFName = $_POST['txtFName'] : $txtFName = "";
  isset($_POST['txtLName'])? $txtLName = $_POST['txtLName'] : $txtLName = "";
  isset($_POST['txtStockImage'])? $stockImage = $_POST['txtStockImage'] : $stockImage = "";
  isset($_POST['txtGroup'])? $txtGroup = $_POST['txtGroup'] : $txtGroup = "administrator";
  isset($_POST['txtStatus'])? $UserStatus = $_POST['txtStatus'] : $UserStatus = "1";  
  $mode = "insertRec";
  
  if($action == "updateRec") {
    $query =
      "UPDATE lpa_users SET
         lpa_user_password = '$txtPassword',
         lpa_user_firstname	= '$txtFName',
         lpa_user_lastname= '$txtLName',
         lpa_user_group = '$txtGroup',
         lpa_user_status= '$UserStatus'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
         header("Location: users.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {
    $query =
      "INSERT INTO lpa_users (
				 lpa_user_username,
				 lpa_user_password,
				 lpa_user_firstname,
				 lpa_user_lastname,
				 lpa_user_group,
				 lpa_user_status
		   ) VALUES (
         '$txtUserName',
         '$txtPassword',
         '$txtFName',
         '$txtLName',
         '$txtGroup',
         '$UserStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: users.php?a=recInsert&txtSearch=".$txtFName);
      exit;
    }
  }

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $txtUserName     = $row['lpa_user_username'];
    $txtPassword   = $row['lpa_user_password'];
    $txtFName   = $row['lpa_user_firstname'];
    $txtLName = $row['lpa_user_lastname'];
    $txtGroup  = $row['lpa_user_group'];
    $UserStatus = $row['lpa_user_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="PageTitle">Users Record Management (<?PHP echo $action; ?>)</div>
    <form name="frmStockRec" id="frmStockRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <input name="txtUserName" id="txtUserName" placeholder="User name" value="<?PHP echo $txtUserName; ?>" style="width: 100px;" title="User Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input type="password" name="txtPassword" id="txtPassword" placeholder="Password" value="<?PHP echo $txtPassword; ?>" style="width: 400px;"  title="Password">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <textarea name="txtFName" id="txtFName" placeholder="Client first name" style="width: 400px;height: 80px"  title="Client Name"><?PHP echo $txtFName; ?></textarea>
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtLName" id="txtLName" placeholder="Client Last name" value="<?PHP echo $txtLName; ?>" style="width: 400px;text-align: right"  title="Last Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtGroup" id="txtGroup" placeholder="Group" value="<?PHP echo $txtGroup; ?>" style="width: 90px;text-align: right"  title="Group of the user">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>User Status:</div>
        <input name="txtStatus" id="txtUserStatusActive" type="radio" value="1">
          <label for="txtUserStatusActive">Enable</label>
        <input name="txtStatus" id="txtUserStatusInactive" type="radio" value="2">
          <label for="txtUserStatusInactive">Disable</label>
      </div>
      <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
      <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" id="btnStockSave">Save</button>
      <button type="button" onclick="navMan('users.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
  <script>
    var stockRecStatus = "<?PHP echo $UserStatus; ?>";
    if(stockRecStatus == "a") {
      $('#txtUserStatusActive').prop('checked', true);
    } else {
      $('#txtUserStatusInactive').prop('checked', true);
    }
    $("#btnStockSave").click(function(){
        $("#frmStockRec").submit();
    });
    function delRec(ID) {
      navMan("useraddedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtPassword").focus();
    },1);
  </script>
<?PHP
build_footer();
?>