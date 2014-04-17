<?php  
	$sql_ora="";
	include ("./commun/include/CustomSql.inc.php");	
	require("./commun/include/connexion_gilda");		
	if(isset($Annuler)) {
		   	$noip="";
		   	$t_nom="";
		   	$t_prenom="";
	}	

?>

<title>Recherche Patients</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/style.css" type="text/css">
<link rel="stylesheet" href="./commun/style/tbl_scroll.css" type="text/css">
<style type="text/css">
<!--
.Style1 {
	color: #0000FF;
	font-weight: bold;
}
-->
</style>
</head>
<script type="text/JavaScript">
	function upperCase(Idval){
		var x=document.getElementById(Idval).value
		document.getElementById(Idval).value=x.toUpperCase()
	}
		
	function CheckSelection(retval){
		if (retval==""){
			alert("Aucune valeur selectionnée!!!");
		   return false;
		}
	document.getElementById('details').innerHTML="Recherche en cours....";
	noip=retval;
	retval = file('./gilda_passages.php?param='+escape(noip))
	document.getElementById('details').innerHTML=retval;
		
	}

	//==============================
	// function d'appel pour AJAX par Mohanraju
	//===============================
	function file(fichier)
	{
		if(window.XMLHttpRequest) // FIREFOX
			xhr_object = new XMLHttpRequest();
		else if(window.ActiveXObject) // IE
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else
			return(false);
		xhr_object.open("GET", fichier, false);
		xhr_object.send(null);
		if(xhr_object.readyState == 4) return(xhr_object.responseText);
		else return(false);
	}	
		
</script>
<body bgcolor="#FFFFFF" text="#000000">
<form>
	<div style="overflow:auto;width:700px;  ">
    <h2 align="center"> Recherche des patients</h2>
		  <table width="350" border="0" cellspacing="0" cellpadding="0" align="center">
		    <?Php  if ($errormsg){
					Print "<tr><td  colspan='2'><font color='#FF0000'> $errormsg </font></td></tr>" ;
				}
			?>
		    
		    <tr> 
		      <td width="39%" align="left"> NIP :  </td>
		      <td width="57%"><input type="text" name="noip" maxlength="10" value="<?Php  Print $noip?>">      </td>
		    </tr>
		    <tr> 
		      <td  align="left">Nom : </td>
		      <td ><input type="text" name="t_nom" value="<?Php  Print $t_nom?>" onBlur="upperCase('nom')">      </td>
		    </tr>
		    <tr>
		      <td align="left">Prénom :</td>
		      <td  ><input type="text" name="t_prenom" value="<?Php  Print $t_prenom?>" onBlur="upperCase('prenom')"></td>
	        </tr>
		    <tr> 
		      <td  colspan="2" align="center"> 
			          <input name="Rechercher" type="submit" class="form_bouton_bleu" value="Rechercher" onClick="show_parent()">      
		              <input class="form_bouton_bleu" type="submit" name="Annuler" value="Vider les champs" id="Annuler">		    
							<input name="fermer" type="submit" class="form_bouton_bleu" value="Fermer">			              
		               </td>
		    </tr>
		  </table>
  </div>
  
<?Php
	//======================================================
	// Parent
	//======================================================
  if($_GET['Rechercher']=="Rechercher") {
		if (strlen($noip)< 8 && strlen($t_nom)< 3 && strlen($t_prenom)< 3) {
			$errormsg="Aucune critère de recherche rempli !!!<br />
							Nip : minimum 7 chiffres <br />
							Nom ou Prénom : minimum 3 caractère   ";
		}else{  
			$fields_ora="SELECT distinct  PAT.NOIP, NMMAL, NMPMAL, NMPATR, CDSEXM, TO_CHAR(DANAIS,'DD/MM/YYYY')  ,NOCOMA from pat" ;
			if (isset($noip) && strlen($noip)> 7){
		   	$sql_ora=" where noip like ('$noip"."%"."') ";			
			}
			if (isset($t_nom) && strlen($t_nom)> 2){
				if (strlen($sql_ora)> 2 ){ 
					$sql_ora.=" and nmmal like ('".strtoupper($t_nom)."%') ";			
				}else{
		   		$sql_ora=" where nmmal like ('".strtoupper($t_nom)."%') ";			
				}
			}
			if (isset($t_prenom) && strlen($t_prenom)> 2){
				if (strlen($sql_ora) > 2) {
					$sql_ora.=" and nmpmal like ('".strtoupper($t_prenom)."%') ";					   		
		   	}else{
		   		$sql_ora=" where nmpmal like ('".strtoupper($t_prenom)."%') ";				
		   	}
		   		
			}
			$sql_ora=$fields_ora.$sql_ora." Order by nmmal,nmpmal";
        	if (strlen($sql_ora) > 0){			
				$result = ociparse($ConnGilda, $sql_ora);
				ociexecute($result);
			}
		}
  	
  	?>
		<table width="700" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFCC">
			<tr  id="idHeader" bgcolor=#A8BBCA  height=30>
                 <th width='100'>NIP</th>
                 <th width='150'>Nom</th>
                 <th width='150'>Prénom</th>
                 <th width='100'>Date de naissance</th>
                 <th width='50'>Sexe</th>
                 <th width='150'>Nom JF</th>
              </tr>              
		</table>          
		<div style="overflow:auto;width:700px; height:150px; ">
		<table width="680" border="1" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
		  <?Php  
			while(ocifetch($result))	{		  	
		  			
		  		$noip=ociresult($result, 1);		    			    			    			    		
				$t_nom=ociresult($result, 2);
				$t_prenom=ociresult($result, 3);
				$t_njf=ociresult($result, 4);
				$ddn=ociresult($result, 6);
				$sexe=ociresult($result, 5);		
		  	 ?>
					<tr class="initial"                       
								onMouseOver="this.className='highlight'"
								onMouseOut="this.className='normal'"    
								onClick="CheckSelection(<?php print $noip?>)" >
				    <td width='100'>&nbsp;				      <?Php  Print $noip;?> </td>
				    <td width='150'>&nbsp; <?Php  Print $t_nom;?></td>
				    <td width='150'>&nbsp; <?Php  Print $t_prenom;?></td>
				    <td width='100'>&nbsp; <?Php  Print $ddn;?></td>
				    <td width='50'>&nbsp;  <?Php  Print $sexe;?></td>
				    <td width='150'>&nbsp; <?Php  Print $t_njf;?></td>
				  </tr>
		  <?Php    }?>

		</table>
	</div>
	    <p>
	      <?php
}
?>
<br />
	     	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Style1">&nbsp;D&eacute;tail du pasages </span>

		<table width="700" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFCC">
			<tr  id="idHeader" bgcolor=#A8BBCA  height=30>
                 <th width='60'>NDA</th>
                 <th width='120'>UH</th>
                 <th width='60'>date entree</th>
                 <th width='60'>date Sortie</th>
              </tr>              
		</table>          
	<div style="overflow:auto;width:700px; height:150px; " id="details"></div>
		<input name="affiche_parent" id="affiche_parent" type="hidden"  value="NON">
		<input name="affiche_child"  id="affiche_child" type="hidden"  value="NON">		  		


	
</form>
</body>
</html>
