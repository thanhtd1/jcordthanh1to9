<?php
/* Smarty version 3.1.33, created on 2019-07-25 13:50:54
  from 'D:\xampp\htdocs\code\proj\lib\templates\System_get.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d39352ecbd511_98737212',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '643bb6dee991a749389bd595c1c801c1bf2c5b2d' => 
    array (
      0 => 'D:\\xampp\\htdocs\\code\\proj\\lib\\templates\\System_get.tpl',
      1 => 1564030248,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:System_head.tpl' => 1,
    'file:System_item.tpl' => 1,
  ),
),false)) {
function content_5d39352ecbd511_98737212 (Smarty_Internal_Template $_smarty_tpl) {
?>{
	"head":{
<?php $_smarty_tpl->_subTemplateRender('file:System_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
$_smarty_tpl->_subTemplateRender('file:System_item.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
}
?>
	}
}<?php }
}
