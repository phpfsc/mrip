<?php
		require_once("../../config.php");
		$PartyID=$_POST['PartyID'];
		$x=1;
		$qry=mysqli_query ($con2,"select * from special_item_rate_master where iPartyID=\"$PartyID\" order by iId");
		if ($qry && mysqli_num_rows($qry))
		{
			echo "<table class=\"table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed\">";
			echo "<thead><tr><th>SNo</th><th>Item Name</th><td>Rate</th></tr></thead><tbody>";
			while ($row=mysqli_fetch_array($qry))
			{
				echo "<tr>";				
				echo "<td>$x</td>";
				echo "<td>$row[cItemName]</td>";
				echo "<td>$row[fRate]</td>";
				echo "</tr>";
				$x++;
			}
			echo "</tbody></table>";
		}
		else 
		{
			echo "<center><span class=\"legend\">No Records Found...</span></center>";
		}
?>