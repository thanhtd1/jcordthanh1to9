<?php
/* Smarty version 3.1.33, created on 2019-07-04 16:37:01
  from 'D:\xampp\htdocs\jcord\proj\lib\templates\User_add.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d1dac9daaeb66_25871482',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8fe960eba6d8ec096c5a7e12796cb780d63d1d2a' => 
    array (
      0 => 'D:\\xampp\\htdocs\\jcord\\proj\\lib\\templates\\User_add.tpl',
      1 => 1562043883,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:User_head.tpl' => 1,
    'file:User_item.tpl' => 1,
  ),
),false)) {
function content_5d1dac9daaeb66_25871482 (Smarty_Internal_Template $_smarty_tpl) {
?>{
	"head":{
<?php $_smarty_tpl->_subTemplateRender('file:User_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	},
	"data":{
<?php
$__section_no_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['data']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_no_0_total = $__section_no_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_no'] = new Smarty_Variable(array());
if ($__section_no_0_total !== 0) {
for ($__section_no_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] = 0; $__section_no_0_iteration <= $__section_no_0_total; $__section_no_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_no']->value['first'] = ($__section_no_0_iteration === 1);
if ((isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['first'] : null)) {
$_smarty_tpl->_subTemplateRender('file:User_item.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
}
?>
	}
}
<?php }
}
