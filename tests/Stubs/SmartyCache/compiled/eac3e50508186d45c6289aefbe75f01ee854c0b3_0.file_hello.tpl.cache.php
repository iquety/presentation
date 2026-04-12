<?php
/* Smarty version 5.8.0, created on 2026-04-12 02:37:51
  from 'file:folder/hello.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69db057f2c9e67_85581266',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eac3e50508186d45c6289aefbe75f01ee854c0b3' => 
    array (
      0 => 'folder/hello.tpl',
      1 => 1775931249,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69db057f2c9e67_85581266 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/application/tests/Stubs/SmartyOne/folder';
$_smarty_tpl->getCompiled()->nocache_hash = '7237303269db057f2af230_45099737';
?>
Hello, <?php echo $_smarty_tpl->getValue('name');?>
!<?php }
}
