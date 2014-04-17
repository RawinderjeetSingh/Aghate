<?php
	session_start();
	
	if (!(session_is_registered("CID"))){
		//include("relogin.php");
		//echo "not loged in";
		//exit;
	}
	$showtable=true;
	include "./commun/include/CustomSql.inc.php";
	include("listemodels.php");
  $db = new CustomSQL($DBName);
	$sql="";
	// register le id_pat dans session
	if($sess_tag=="NEW"){
		if (isset($_SESSION['id_pat']))
			session_unregister("id_pat");
		$_SESSION["id_pat"] = $_GET['id_pat'];	
   	$id_pat=$_GET['id_pat'];
	}else{
	   $id_pat=$_SESSION["id_pat"]  ;		
	}
	
	if(empty($id_pat)){
		$errormsg="Aucun patient selectionée";
		$showtable=false;
	}else{
		// get patients info
		$result=$db->GetPatInfo($id_pat);				
		$noip = $result[0]["noip"];
		$nom = $result[0]["nom"];
		$prenom = $result[0]["prenom"];
		$ddn = date_Mysql2Normal($result[0]["ddn"]);
		
		// get bilan _info 
	
		
	}	

	$results=$db->GetAllBilans($id_pat);	

?>
<html>
<head>
<title>Liste des bilans</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<?php 
	include("head.php"); 
include("menu_main.php");

	if($errormsg)
		Print $errormsg;
	
	if ($showtable){
		include("patients_info.php");
?>
	
<table width="98%" border="1" align="center"> <tr><td>	
	<form action="bilan_clinique.php?tag=NEW">
	 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"> 
	  <tr> 
	  	 <input type="hidden" name="tag" value="NEW">
	  	 <input type="hidden" name="id_bilan" value="<?Php print $id_bilan;?>">
	    <td height="20" colspan="8"><input class="form_bouton_bleu" type="submit" name="Ajouter" value="Ajouter">
	    </td>
	  </tr>
	</table>
	</form>

	<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center">
	  <tr> 
	    <th colspan="10" class="page_title">Liste 
	      des bilans</th>
	  </tr>
	  <tr class="table_top"> 
	    <td width="33%" > N° Bilan</td>
	    <td width="33%" > Date     </td>
	    <td width="11%" > Clinique    </td>
	    <td width="11%" > Biologie   </td>
	    <td width="11%" > Staging   </td>	    
	  </tr>
	  <?php 
	  	$Last_id_bilan="";
		for($i=0; $i < count($results);$i++) {
			if($bgcolor==$table_bgcolor1)
				$bgcolor=$table_bgcolor2;
			else
				$bgcolor=$table_bgcolor1;
				
			$id_bilan=$results[$i]['id_bilan'];	
			if ($Last_id_bilan==$id_bilan){
				//même id bilan on affihe pas dans le liste des bilan pas 
			}else{	
				$Last_id_bilan=$id_bilan;
				$href="bilan_clinique.php?id_bilan=$id_bilan";
				$href_bilan="<a href='bilan_clinique.php?id_bilan=$id_bilan&tag=AFFICHER&ses_tag=NEW'> Modifier</a> ";	
				if (isset($results[$i]['id_bio'])){
					$href_biologie="<a href='bilan_biologie.php?id_bilan=$id_bilan&tag=AFFICHER&ses_tag=NEW'> Modifier</a> ";	
				}else{
					$href_biologie="<a href='bilan_biologie.php?id_bilan=$id_bilan&tag=NEW&ses_tag=NEW'> Ajouter</a> ";	
				}
				if (isset($results[$i]['id_staging'])){
					$href_staging="<a href='bilan_staging.php?id_bilan=$id_bilan&tag=AFFICHER&ses_tag=NEW'> Modifier</a> ";											
				}else{
					$href_staging="<a href='bilan_staging.php?id_bilan=$id_bilan&tag=NEW&ses_tag=NEW'> Ajouter</a> ";											
			   }
				$type=GetReponse($ListeJour,$results[$i]['cli_type']);
				// claculation IPI, IPIFI ..
				//AGE
				if ($results[$i]['age'] > 60 )
					$calc_age=1;
				else
					$calc_age=0;
				//LDH	
				if( $result[$i]['ldh_valeur']	>$result[$i]['ldh_normales']	)
					$calc_ldh=1;
				else
					$calc_ldh=0;
				//PS
				if( $result[$i]['ps']	>= 2	)
					$calc_ps=1;
				else
					$calc_ps=0;
					
				$ipi=$calc_age + $calc_ldh + $calc_ps ;
				$flipi="&nbsp;";
				$rai="&nbsp;";
				$bine="&nbsp;";
		  ?>
				  <tr> 
				    <td width="33%"  bgcolor="<?Php print $bgcolor?>" align="center" > <div align="center"><?Php print $id_bilan;?> </div></td>
				    <td width="33%"  bgcolor="<?Php print $bgcolor?>" align="left"> <div align="center"><?Php print IsEmpty(date_Mysql2Normal($results[$i]['date_cli']));?></div></td>
				    <td width="11%"  bgcolor="<?Php print $bgcolor?>" align="left"> <div align="center"><?Php print $href_bilan;?></div></td>			    
				    <td width="11%"  bgcolor="<?Php print $bgcolor?>" align="left"> <div align="center"><?Php print $href_biologie;?></div></td>
				    <td width="11%"  bgcolor="<?Php print $bgcolor?>" align="left"> <div align="center"><?Php print $href_staging;?></div></td>			    			    
	    </tr>
			
		<?php  
			} // fin if pour me^me id_bilan
		} // fin for
		if ($i==0){
			Print "<tr><td class='texte_rouge' colspan='10'>Aucune Bilan trouvée </td></tr>";
		}
	  ?>  
	</table>
	<?php }// showtable
	?>
</td>
</tr></table>
</body>
</html>
