<div class="new_debate"> 
<h2>New Argument for Debate "<?= $debate['Debate']['title'] ?>"</h2>     
    <?php echo $form->create('Argument', array('action' => 'new_argument'));?> 
        <?php echo $form->hidden('Debate.id');?> 
        <?php echo $form->textarea('argument', array('rows'=>'5'));?> 
        <?php echo $form->submit('Post');?> 
    <?php echo $form->end(); ?> 
</div> 