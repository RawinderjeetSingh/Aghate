<?php
function Check_sexe($date,$room_id){
	// get room name
		$sql = "select room_name, description from agt_room where id=".$room_id;
		$res = grr_sql_query($sql);
	
		if ($res) {
			$row = grr_sql_row($res, $i);
			$room_desc=$row[0];
		}else{
			return "";
		}
		if ($room_desc){
			$room_desc=substr($room_desc,0,2);// première deux chars
		}else{
			return "";		
		}
		$date_f=$date." 23:59:00";
		$date_t=$date." 00:00:00";		
		
		//qry model
		$sql_model=" SELECT agt_room.room_name,TYPE FROM agt_loc, agt_room
				WHERE agt_room.id = agt_loc.room_id
				AND `timestamp` < '2009-01-12 23:59:00'
				AND `timestamp` > '2009-01-11 00:00:00'
				AND agt_room.room_name LIKE ('40%')";

		// nbr des male et female dans le chambre
		$sql="SELECT agt_room.room_name, count(TYPE ) AS M, '0' AS F FROM agt_loc, agt_room
				where agt_room.id=agt_loc.room_id
				AND timestamp < '$date_f' and `timestamp` > '$date_t'
				AND agt_room.room_name LIKE ('$room_desc%')
				AND TYPE = 'M'
				GROUP BY room_name				
				UNION 
				SELECT agt_room.room_name, '0' AS M, count(TYPE ) AS F FROM agt_loc, agt_room
				WHERE agt_room.id = agt_loc.room_id
				AND timestamp < '$date_f' and `timestamp` > '$date_t'
				AND agt_room.room_nameLIKE ('$room_desc%')
				AND TYPE = 'F'
				GROUP BY room_name";
echo $sql;				
				
}

$r=Check_sexe("2009-01-12",16);

/*


SELECT agt_room.room_name, count(TYPE ) AS M, '0' AS F FROM agt_loc, agt_room
WHERE agt_room.id = agt_loc.room_id
AND `timestamp` < '2009-01-12 23:59:00'
AND `timestamp` > '2009-01-12 00:00:00'
AND agt_room.room_name LIKE (
'40%'
)
AND TYPE = 'M'
GROUP BY room_name
UNION SELECT agt_room.room_name, '0' AS M, count(
TYPE ) AS F
FROM agt_loc, agt_room
WHERE agt_room.id = agt_loc.room_id
AND `timestamp` < '2009-01-12 23:59:00'
AND `timestamp` > '2009-01-12 00:00:00'
AND agt_room.room_name LIKE (
'40%'
)
AND TYPE = 'F'
GROUP BY room_name
*/


?>
