<?php
/* Smarty version 3.1.33, created on 2019-07-03 18:24:22
  from 'D:\xampp\htdocs\jcord\proj\lib\templates\User_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d1c74464130d8_28508794',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ac4e12748edd8fa07637be4d233d0a8ac33fb53f' => 
    array (
      0 => 'D:\\xampp\\htdocs\\jcord\\proj\\lib\\templates\\User_list.tpl',
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
function content_5d1c74464130d8_28508794 (Smarty_Internal_Template $_smarty_tpl) {
?>{
	"head":{
<?php $_smarty_tpl->_subTemplateRender('file:User_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
<?php $_smarty_tpl->_subTemplateRender('file:User_item.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
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
