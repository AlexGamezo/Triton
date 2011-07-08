<div class="new_posting"> 
<h2>Create Posting</h2>     
    <?php echo $form->create('Posting', array('action' => 'new_posting'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 