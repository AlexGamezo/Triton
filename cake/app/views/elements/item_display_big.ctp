<div>
	<table>
		<th>Title</th>
		<th>Author</th>
	<?php 		
		foreach($itemsitems as $item)
		{
			echo "
		<tr>
			<td>{$item[$item_type]['title']}</td>
			<td>{$item['User']['username']}</td>
		</tr>";
		}
	?>
	</table>
</div>