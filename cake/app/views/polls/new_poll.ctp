<div class="new_poll"> 
<h2>Create Poll</h2>     
    <?php echo $form->create('Poll', array('action' => 'new_poll'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('end_time');?>
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?> 
        <?php echo $form->input('PollOption.0.option');?> 
        <?php echo $form->input('PollOption.1.option');?> 
        <?php echo $form->input('PollOption.2.option');?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 