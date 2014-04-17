<?php

function OraSelect($conn,$sql){
	if ($sql=="")
	{
		return false;
	}
	if (!isset($conn))
	{
		echo "Erreur connextion Base Oracle !!!";
		return false;
	}
	$result = ociparse($conn, $sql);
	ociexecute($result);
	$row=0;	
	$data=array();
		while(ocifetch($result))
		{
			$ncols = ocinumcols($result);
			$c_line=array();
			for ($i = 1; $i <= $ncols; $i++) {
	         $column_name  = ocicolumnname ($result, $i);
	         $column_value = oci_result($result, $i);
       		$c_line[$column_name]=$column_value;
   		}
			$data[$row]=$c_line;
			$row++;
		}
		
		return $data;	

}			

function OraLogoff(){
	ocilogoff($c1);
	}
?>
