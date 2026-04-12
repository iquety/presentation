<?php
/* Smarty version 5.8.0, created on 2026-04-12 02:49:23
  from 'file:folder/hello.html' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.0',
  'unifunc' => 'content_69db0833490099_74032807',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '60409a2bbac6da2564d33789cd4acfe70de7b826' => 
    array (
      0 => 'folder/hello.html',
      1 => 1775931249,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_69db0833490099_74032807 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/application/tests/Stubs/SmartyOne/folder';
$_smarty_tpl->getCompiled()->nocache_hash = '156575461969db0833475ad5_77631050';
?>
Hello, <?php echo $_smarty_tpl->getValue('name');?>
!<?php }
}
