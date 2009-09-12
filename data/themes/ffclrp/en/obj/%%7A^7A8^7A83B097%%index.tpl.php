<?php /* Smarty version 2.6.16, created on 2008-03-31 18:29:47
         compiled from /home/magsilva/Projects-Personal/CoTeia-2.5/data/themes/ffclrp/en/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/home/magsilva/Projects-Personal/CoTeia-2.5/data/themes/ffclrp/en/index.tpl', 33, false),)), $this); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h1><img src="themes/ffclrp/images/logo.png" alt="CoTeia - Web Based Collaborative Edition Tool" />CoTeia</h1>
<hr align="right" />

<h2>Active wikis:</h2>

<?php if (count ( $this->_tpl_vars['currentWikis'] ) == 0): ?>
<em>(There is no currently active wikis).</em>
<?php else: ?>
<ul>
	<?php $_from = $this->_tpl_vars['currentWikis']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['wiki']):
?>
	<li>
		<div class="swiki">
			<span class="swiki-title"><a href="index.php?do=View&type=Wiki&name=<?php echo $this->_tpl_vars['wiki']->id; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></span>
			(Admin: <a href="mailto:<?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->maintainerEmail)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->maintainer)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>)
		</div>
	</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>
<?php endif; ?>

<?php if (count ( $this->_tpl_vars['otherWikis'] ) != 0): ?>
<h2>Old (and probably innactive) wikis:</h2>
<ul>
<?php $_from = $this->_tpl_vars['otherWikis']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['wiki']):
?>
	<li>
		<div class="swiki">
			<span class="swiki-title"><a href="index.php?do=View&type=Wiki&name=<?php echo $this->_tpl_vars['wiki']->id; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></span>
			(Admin: <a href="mailto:<?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->maintainerEmail)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['wiki']->maintainer)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>)
		</div>
	</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>