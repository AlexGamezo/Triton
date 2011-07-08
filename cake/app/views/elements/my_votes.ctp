<div>
	<table>
		<th>Title</th>
		<th>Date</th>
	<?php 		
		foreach($my_votes as $vote)
		{
			echo "
		<tr>
			<td>{$vote['title']}</td>
			<td>{$vote['date']}</td>
		</tr>";
		}
	?>
	</table>
</div>