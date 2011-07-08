<div class="edit_poll"> 
<h2>Edit Poll</h2>     
    <?php echo $form->create('Poll', array('action' => 'edit'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('end_time');?>
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?>
		<?php
			$i = 0;
			foreach($this->data['PollOption'] as $option)
			{
				echo $form->input("PollOption.$i.id");
				echo $form->input("PollOption.$i.option");
				$i++;
			}
		?>
        <?php echo $form->submit('Update');?> 
    <?php echo $form->end(); ?> 
</div> 