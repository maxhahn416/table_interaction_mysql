<?php

	$id_array = array();
	$con=mysqli_connect("localhost","root","","rets");
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	// Perform queries 
	$result = mysqli_query($con,"SELECT * FROM wp_users");

	for($i = 0; $i<mysqli_num_rows($result);$i++)
	{
		$row=mysqli_fetch_array($result,MYSQLI_NUM);
		array_push($id_array,$row[0]);
	}

	for($i = 0; $i<count($id_array);$i++)
	{
		$account_status = false;
		$sql = "SELECT * from wp_usermeta WHERE user_id = $id_array[$i]";
		$result = mysqli_query($con,$sql);
		for($j = 0; $j<mysqli_num_rows($result);$j++)
		{
			$row=mysqli_fetch_array($result,MYSQLI_NUM);
			if(($row[4] == "Y")||($row[4] == "anything"))
			{
				$account_status = true;
				break;
			}
		}

		if($account_status == true)
		{
			$update = "UPDATE wp_usermeta SET meta_value='Inactive' WHERE user_id = $id_array[$i] AND meta_key ='account_status'";
			mysqli_query($con,$update);
		}
		else
		{
			$update = "UPDATE wp_usermeta SET meta_value='Approved' WHERE user_id = $id_array[$i] AND meta_key ='account_status'";
			mysqli_query($con,$update);
		}
	}
	mysqli_close($con);

?>