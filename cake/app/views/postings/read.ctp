<div>
	
	<div>
		<h2><?= $posting['Posting']['title'] ?></h2>
		<?= $session->flash() ?>
		<div>Posted by <?= $posting['User']['full_name'] ?> on <?= $posting['Posting']['created'] ?> - <?= $posting['Posting']['vote_count'] ?> people like this (<?= $html->link('Vote Up', array('controller'=>'postings', 'action'=>'vote', $posting['Posting']['id'])) ?>)</div>
		<? if($posting['Posting']['created'] != $posting['Posting']['modified']) echo "updated on ".$posting['Posting']['modified'].'<br />'; ?>
		<br />
		<div><?= $posting['Posting']['content'] ?></div>
	</div>
</div>