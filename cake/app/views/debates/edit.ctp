<div class="edit_debate"> 
<h2>Edit Debate</h2>     
    <?php echo $form->create('Debate', array('action' => 'edit'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?> 
        <?php echo $form->input('end_time');?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 