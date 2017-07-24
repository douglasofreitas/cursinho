<div class="groups view">
<h2><?php  __('Group');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Group Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['group_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nome'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $group['Group']['nome']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit Group', true), array('action' => 'edit', $group['Group']['group_id'])); ?> </li>
		<li><?php echo $html->link(__('Delete Group', true), array('action' => 'delete', $group['Group']['group_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['group_id'])); ?> </li>
		<li><?php echo $html->link(__('List Groups', true), array('action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Group', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
