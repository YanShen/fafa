<?php
include("classes/http.php");
include('classes/main.class.php');

class BlockList extends UCCASS_Main {
	
	Function initial() {
        $data = array();
        $template = 'admin.tpl';

        $admin_priv = $this->_CheckLogin(0,ADMIN_PRIV,'block_list.php');

        if(!$admin_priv)
        {
            $template = 'login.tpl';
            if(isset($_REQUEST['password']))
            {
                $data['message'] = 'Incorrect Username and/or Password';
                $data['username'] = $this->SfStr->getSafeString($_REQUEST['username'],SAFE_STRING_TEXT,1);
            }
            $data['page'] = 'block_list.php';

	        $this->smarty->assign_by_ref('data',$data);
	        $retval = $this->smarty->Fetch($this->template.'/'.$template);
	
	        echo $this->com_header() . $retval;
	         
	        return false;
	     } else {
	      	return true;
	     }
	}
	
	Function listNames() {
		$rs = $this->Query("SELECT * from {$this->CONF['db_tbl_prefix']}block_list");
		if($rs === FALSE)
		{ $this->error("Error loading Block_List " . $this->db->ErrorMsg()); return; }
		
		$result = array();
		while($r = $rs->FetchRow($rs)) {
			$result["id"][] = $r["id"];
			$result["name"][] = $r["name"];
		}
		return $result;
	}
	
	Function insertName($names) {
		$result = true;
		$nameArray = explode("\n", $names) ;
		foreach($nameArray as $name) {
			$name = trim($name);
			if($name == "") continue;
			
			$rs = $this->Query("insert into {$this->CONF['db_tbl_prefix']}block_list (name) values ('$name')");
			if($rs === FALSE)
			{ $this->error("Error inserting Block_List " . $this->db->ErrorMsg()); $result = false; }
			else {
				echo $name." 成功<br>\n";
			}
		} 
		return $result;
	}
	
	Function delete($ids) {
		foreach($ids as $id) {
			$rs = $this->Query("delete from {$this->CONF['db_tbl_prefix']}block_list where id=$id");
			if($rs === FALSE)
			{ $this->error("Error delete block_list " . $this->db->ErrorMsg()); return false; }
		} 
		return true;
	}
	
	Function updateName($origId, $newName) {
		$rs = $this->Query("update {$this->CONF['db_tbl_prefix']}block_list set name='$newName' where id=$origId");
		if($rs === FALSE)
		{ $this->error("Error update block_list " . $this->db->ErrorMsg()); return false; }
		else {
			return true;
		}
	}
}

?>

<?
	$bl = new BlockList();
	$result = array();
	if(!$bl->initial()) return;
	
	if($_REQUEST["act"]=="add") {
		$rst = $bl->insertName($_REQUEST["newNames"]);
		echo $rst?"新增成功":"新增失敗";
	} else if($_REQUEST["act"]=="delete") {
		$rst = $bl->delete($_REQUEST["id"]);
		echo $rst?"刪除成功":"刪除失敗";
	} else if($_REQUEST["act"]=="update") {
		$rst = $bl->updateName($_REQUEST["origId"], $_REQUEST["newName"]);
		echo $rst?"修改成功":"修改失敗";
	} else if($_REQUEST["act"]=="list") {
		$result = $bl->listNames();
	}

?>
<?=$bl->com_header()?> 
	<body>
	<script type="text/javascript">
		function addName() {
			if(document.form1.newNames.value == "") {
				alert("請輸入姓名, 以換行區分 " );
				return;
			} 
			document.form1.act.value="add";
			document.form1.submit();
		}

		function deleteNames() {
			document.form1.act.value="delete";
			document.form1.submit();
		}
		
		function updateName(origId, origName) {
			var newName = prompt("要將姓名要修改為", origName);
			if(newName != "") {
				document.form1.act.value="update"; 
				document.form1.origId.value=origId;
				document.form1.newName.value=newName;
				document.form1.submit();
			}
		}
		function listNames() {
			document.form1.act.value="list"; 
			document.form1.submit();
		}
	</script>
<table align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="<?=$bl->CONF["images_html"]?>/box_left.gif" border="0" width="14"></td>
    <td background="<?=$bl->CONF["images_html"]?>/box_bg.gif">禁止名單</td>
    <td width="14"><img src="<?=$bl->CONF["images_html"]?>/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table align="center" class="bordered_table">
	<tr>
	  <td align="center">
		[ <a href="<?=$bl->CONF["html"]?>/admin_surveys.php">列表</a> ]
        &nbsp;|&nbsp;[<a href="<?=$bl->CONF["html"]?>/new_survey.php">建立</a>]
        &nbsp;|&nbsp;[<a href="<?=$bl->CONF["html"]?>/black_list.php">黑名單</a>]
        &nbsp;|&nbsp;[<a href="<?=$bl->CONF["html"]?>/block_list.php">禁止名單</a>]
	  </td>
	</tr>
  <tr> 
   	<td>
		<form name="form1" action="block_list.php" method="POST">
			<input type="hidden" name="act" value="">
			<input type="hidden" name="newName">
			<input type="hidden" name="origId">
		<table align="center"  style="border-collapse: collapse;" border="1" bordercolor="#888888">
			<tr><td colspan="4" align="center"><input type="button" value="新增" onclick="addName()">

<?
			if(sizeof($result)) { ?>
				<input type="button" value="刪除" onclick="deleteNames()">
<?
			} ?>
				<input type="button" value="列出名單" onclick="listNames()"> </td></tr> 
		  <tr>
		  	<td colspan="4">
		  		<textarea name="newNames" cols="30" rows="8"></textarea>
		    </td>
		  </tr>
<?
			for($i=0; $i< count($result["id"]); $i++) { ?>
				<tr>
					<td><input type="checkbox" name="id[]" value="<?=$result["id"][$i]?>"/></td>
					<td><?=($i+1)?></td>
					<td><?=$result["name"][$i]?></td>
					<td><input type="button" value="修改" onclick="updateName( <?=$result["id"][$i]?>, '<?=$result["name"][$i]?>')" ></td>
				</tr>
<?
			} ?>

		</table>
		</form>
	</td>
  </tr> 
  <tr>
   	<td> 
        [<a href="<?=$bl->CONF["html"]?>/new_answer_type.php">新增答案類型</a>]
        &nbsp;|&nbsp;
        [<a href="<?=$bl->CONF["html"]?>/edit_answer.php">編輯答案類型</a>]
        &nbsp;|&nbsp;
        [<a href="<?=$bl->CONF["html"]?>/index.php">主畫面</a>]
        &nbsp;|&nbsp;[<a href="<?=$bl->CONF["html"]?>/admin.php">管理</a>]
   	</td>
  </tr>	
</table>
	</body>
</html>