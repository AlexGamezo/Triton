<div class="edit_posting"> 
<h2>Edit Posting</h2>     
    <?php echo $form->create('Posting', array('action' => 'edit'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 