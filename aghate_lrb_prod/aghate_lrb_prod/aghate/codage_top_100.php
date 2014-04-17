<?php include "./commun/include/admin.inc.php";
$grr_script_name = "codes.php";
$back = '';
$service="470"; // hépato Gastro
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);

if ((authGetUserLevel(getUserName(),-1) < 3) and (authGetUserLevel(getUserName(),-1,'user') !=  1))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, $area,$back);
    exit();
}
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";


if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
$urm=$_SESSION["URM"];
?>
<script type="text/JavaScript">
	// modifier une enregistrement
	function CheckSelection(retval){
		if (retval==""){
			alert("Aucune valeur selectionnée!!!");
		   return false;
		}
		retval=retval.split("|");
		if (confirm("Voulez vous modifier ce code \n "+ retval[1])){
			var tmode = document.getElementById('t_mode');							
			tmode.value="MODIF";
	   	var prot = document.getElementById('id');
			prot.value=retval[0];
			document.form1.submit();
		}
	}
	// suprimmer une enregistrement
	function sup_this(){
		if (confirm("Voulez vous suprimmer cette enregistrement")){
				var tmode = document.getElementById('t_mode');							
				tmode.value="DELETE";
				document.form1.submit();
		}		
	}	
</script>		
<link rel="stylesheet" href="./commun/style/tbl_scroll.css" type="text/css">
<div align="center" style="overflow:auto;width:600px;" >
  <h2>Les Diagnostics et les Actes PMSI</h2>
</div>
<?php 
	if($t_mode=="MODIF")$tmode="MODIF";
	if(!isset($tmode))$tmode="AFFICHE";
	
	include "./commun/include/CustomSql.inc.php";
	$db = New CustomSQL($DBName);
	// supression
  	if($t_mode=="DELETE"){			
		$sql="DELETE from grr_top100  where id ='$id'";
		$db->delete($sql);
		$tmode="AFFICHE";				
	}			
	// modification ou nouvelle enreg.
	if (isset($Enregistrer)){
		// vérifications des données
		$err="";
		if (strlen($code)==0)$err.=" code  Vide !!! : $code<br />";
		if (strlen($description) < 2) 	$err.=" description vide /invalide !!! : $description <br />";
		if (strlen($err) > 0 ){
		}else{
    		if($tmode=="Nouveau"){			
				$sql="INSERT INTO grr_top100 (service,tag,code,description) values('"
							.protect_data_sql($urm)."','"
							.protect_data_sql($tag)."','"
							.protect_data_sql($code)."','"
							.protect_data_sql($description)."')";
				//echo $sql;							
				$db->insert($sql);
			}
	    	if($tmode=="MODIF"){			
				$sql="UPDATE grr_top100 set 
				code  ='$code',
				description      ='".protect_data_sql($description)."' 
				where id ='$id'";
				$db->insert($sql);
			}
		$tmode="AFFICHE";							
		}
	}else{
		// on initialise les données
		$code="";
		$description="";	
	}
		
	?>

	<?php 
	if (!isset($tag))$tag="DP";
	$sql="select * from grr_top100 where tag='$tag' and service='$urm' order by code ";
	$results=$db->select($sql);
	?>
		<form name="form1" >	 
		 <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFCC">
					 <thead class="fixedHeader">
		         	<tr bgcolor=#A8BBCA  height=30>
		         	  <th colspan="4"><label>
		         	    <select name="tag" id="tag" onchange="submit()">
		         	      <option value="DP" <?php if ($tag=="DP") echo "selected";?> >Diagnostic Princiapal</option>
		         	      <option value="DR" <?php if ($tag=="DR") echo "selected";?>>Diagnostic Reli&eacute;</option>
		         	      <option value="DAS" <?php if ($tag=="DAS") echo "selected";?>>Diagnostic Associ&eacute;e</option>
		         	      <option value="ACTES" <?php if ($tag=="ACTES") echo "selected";?>>Actes</option>
	         	        </select>
		         	  </label></th>
		         	  </tr>
		         	<tr  id="idHeader" bgcolor=#A8BBCA  height=30>
		                 <th width='100'>Code</th>
		                 <th width='600'>Description</th>
		             </tr>              
		          </thead>             
		</table>          
		<div style="overflow:auto;width:600px; height:150px; ">
		<table width="580" border="1" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
			<?Php   
							for($i=0; $i < count($results);$i++) {
								$id_prt=$results[$i]['id'];	
								$code=$results[$i]['code'];
								$description=$results[$i]['description'];						
								$retval="'".$id_prt."|".$code."|".htmlspecialchars($description)."'";
											
								Print "<tr class=\"initial\" 
											onMouseOver=\"this.className='highlight'\" 
											onMouseOut=\"this.className='normal'\" 
											onClick=\"CheckSelection(".$retval.")\">";
							
								Print "<td width='30'>".$results[$i]['code']. " </td>";
								Print "<td width='300'>".$results[$i]['description']. " </td>";
								Print "</tr>";
								
		
						 }
						if ($i==0){
							Print "<tr><td colspan='6'>Aucun Code trouvé </td></tr>";
						}
					  ?> 
		</table>
		</div >
				<br />
		<div align ="center" style="overflow:auto;width:600px;  ">
 <?php
 	if($Annuler=="ANNULER")$tmode="AFFICHE";
 	if($t_mode=="MODIF")$tmode=$t_mode; 	
   if((strcmp($tmode,"AFFICHE") == 0)) { ?>		
	        <input type="submit" name="tmode" value="Nouveau">		
    <?php }?>    
	        <input type="hidden" name="t_mode" id="t_mode" value="">			        
			  <input type="hidden" name="id"  id="id"  value="<?php print $id?>">			 
	        <input type="hidden" name="cur_mode"  id="cur_mode"  value="<?php print $tmode?>">	        	        			  
		</div>	
 	
<?php 

	//---------------------------------------------------
	// affiche l'affichage l'eerur
	//---------------------------------------------------
		if (strlen($err) > 0 ){
			echo "<br /><div color='red'>$err</div><br />";
		}	
	//---------------------------------------------------
	// Nouveau
	//---------------------------------------------------		
  if($tmode=="Nouveau"){
			$code="";
			$description="";	
  	?>
	  
	  <table width="46%" border="0">
	
	    <tr>
	      <td colspan="2" height="30" align="center"> <font size ="2" color="#000099"><b> 
	        Nouveau code</b></font></td>
	  </tr>
	    <tr> 
	      <td width="45%" align="right" class="textnoraml">Code</td>
	      <td width="60%"> 
	        <input type="text" name="code" size="20" maxlength="20"  value="<?php echo $code?>" >      </td>
	    </tr>
	    <tr> 
	      <td width="45%" align="right" class="textnoraml"> Description</td>
	      <td width="60%"> 
          <input type="text" name="description" size="75" maxlength="255"  value="<?php echo $description?>"Onblur="return cUpper(this)" >      </td>
	    </tr>
	    
	    <tr> 
	      <td width="45%" class="textnoraml" align="right">URM</td>
	      <td width="60%"> 
	        <input type="text" name="service" size="10"  maxlength="4"  value="<?php echo $urm;?> " readonly="readonly"> 
	        </td>
	    </tr>
	    

	    <tr> 
	      <td width="45%">	     </td>
	      <td width="60%"> 
	        <input type="submit" name="Enregistrer" value="Enregistrer">
	        <input type="submit" name="Annuler" value="ANNULER">			        	        	        		        	        
        <input type="hidden" name="tmode" value="Nouveau">	    </tr>
	  </table>


<?php } 
	//---------------------------------------------------
	// MODIFICATION
	//---------------------------------------------------
	 if($tmode=="MODIF"){
			$sql="select * from grr_top100 where id='$id'";
			$results=$db->select($sql);
			
			$id=$results[0]['id'];	
			$code=$results[0]['code'];
			$description=$results[0]['description'];						
			$date_deb=Date_Mysql2Normal($results[0]['date_deb']);
			$date_fin=Date_Mysql2Normal($results[0]['date_fin']);


?>	
	
	  
	  <table width="46%" border="0">
	
	    <tr>
	      <td colspan="2" height="30" align="center"> <font size ="2" color="#000099"><b> 
	        Modifier un  code</b></font></td>
	  </tr>
	    <tr> 
	      <td width="45%" align="right" class="textnoraml">code</td>
	      <td width="60%"> 
	        <input type="text" name="code" size="20" maxlength="20"  value="<?php echo $code?>" >      </td>
	    </tr>
	    <tr>
          <td align="right" class="textnoraml"> Description</td>
	      <td><input type="text" name="description" size="75" maxlength="255"  value="<?php echo $description?>"onblur="return cUpper(this)" />          </td>
        </tr>
	    
	    <tr>
          <td class="textnoraml" align="right">URM</td>
	      <td><input type="text" name="service" size="30"  maxlength="4"  value="<?php echo $service;?>"  readonly="readonly"/>
          </td>
        </tr>
	    
	    <tr> 
	      <td width="45%">	     </td>
	      <td width="60%"> 
	        <input type="hidden" name="tmode" value="MODIF">	        	      
	        <input type="submit" name="Enregistrer" value="ENREGISTRER">
        	  <input type="submit" name="Annuler" value="ANNULER">	 
			  <input type="button" name="SUPRIMMER" value="SUPRIMMER" onclick="sup_this()">	         	  
        	     </tr>
	  </table>


<?php }?>
	</form>
