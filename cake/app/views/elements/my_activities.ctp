<div>
	<table>
		<th>TYPE</th>
		<th>Title</th>
		<th>Summary</th>
		<th>Date</th>
		<th>Votes</th>
	<?php 		
		foreach($my_activities as $activity)
		{
			echo "
		<tr>
			<td>{$activity['type']}</td>
			<td>{$activity['title']}</td>
			<td>{$activity['summary']}</td>
			<td>{$activity['date']}</td>
			<td>{$activity['vote_count']}</td>
		</tr>";
		}
	?>
	</table>
</div>