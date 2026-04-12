<?php
/* Smarty version 5.8.0, created on 2026-04-12 02:25:17
  from 'file:hello.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69db028d4759e2_81718564',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fa2c1919407f6728e64840f822022164320da1c7' => 
    array (
      0 => 'hello.tpl',
      1 => 1775931249,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69db028d4759e2_81718564 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/application/tests/Stubs/SmartyOne';
$_smarty_tpl->getCompiled()->nocache_hash = '162154696869db028d459fc3_90951032';
?>
Hello, <?php echo $_smarty_tpl->getValue('name');?>
!<?php }
}
