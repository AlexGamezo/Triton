<div class="new_debate"> 
<h2>Create Debate</h2>     
    <?php echo $form->create('Debate', array('action' => 'new_debate'));?> 
        <?php echo $form->input('title');?> 
        <?php echo $form->input('summary');?> 
        <?php echo $form->input('content');?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 