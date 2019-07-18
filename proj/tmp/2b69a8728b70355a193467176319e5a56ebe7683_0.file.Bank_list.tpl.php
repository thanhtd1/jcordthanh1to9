<?php
/* Smarty version 3.1.33, created on 2019-07-18 13:24:25
  from 'D:\xampp\htdocs\jcord\proj\lib\templates\Bank_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d2ff4796baa21_87093816',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b69a8728b70355a193467176319e5a56ebe7683' => 
    array (
      0 => 'D:\\xampp\\htdocs\\jcord\\proj\\lib\\templates\\Bank_list.tpl',
      1 => 1563423849,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:Bank_head.tpl' => 1,
    'file:Bank_item.tpl' => 1,
  ),
),false)) {
function content_5d2ff4796baa21_87093816 (Smarty_Internal_Template $_smarty_tpl) {
?>{
	"head":{
<?php $_smarty_tpl->_subTemplateRender('file:Bank_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	},
	"data":{
		"count":<?php echo count($_smarty_tpl->tpl_vars['data']->value);?>
,
		"entries":[
<?php
$__section_no_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['data']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_no_0_total = $__section_no_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_no'] = new Smarty_Variable(array());
if ($__section_no_0_total !== 0) {
for ($__section_no_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] = 0; $__section_no_0_iteration <= $__section_no_0_total; $__section_no_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_no']->value['last'] = ($__section_no_0_iteration === $__section_no_0_total);
?>
			{
<?php $_smarty_tpl->_subTemplateRender('file:Bank_item.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
if ((isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['last'] : null)) {?>
			}
<?php } else { ?>
			},
<?php }
}
}
?>
		]
	}
}
<?php }
}
