<?php
/* Smarty version 3.1.33, created on 2019-07-08 16:00:06
  from 'D:\xampp\htdocs\jcord\proj\lib\templates\Bank_head.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d22e9f68003a2_93250047',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3f9d125053422e0ae2176471d8dc697171e5b8c9' => 
    array (
      0 => 'D:\\xampp\\htdocs\\jcord\\proj\\lib\\templates\\Bank_head.tpl',
      1 => 1562553372,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d22e9f68003a2_93250047 (Smarty_Internal_Template $_smarty_tpl) {
if (count($_smarty_tpl->tpl_vars['head']->value) == 0) {?>
		"status":"SUCCEEDED"
		,"message":"succeeded"
<?php } else { ?>
		"status":"FAILED"
		,"message":"failed"
		,"reasons":[
<?php
$__section_no_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['head']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_no_1_total = $__section_no_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_no'] = new Smarty_Variable(array());
if ($__section_no_1_total !== 0) {
for ($__section_no_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] = 0; $__section_no_1_iteration <= $__section_no_1_total; $__section_no_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_no']->value['last'] = ($__section_no_1_iteration === $__section_no_1_total);
?>
	 	 {
			"what":"<?php echo $_smarty_tpl->tpl_vars['head']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] : null)]['what'];?>
"
			,"how":"<?php echo $_smarty_tpl->tpl_vars['head']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] : null)]['how'];?>
"
			,"why":"<?php echo $_smarty_tpl->tpl_vars['head']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] : null)]['why'];?>
"
			,"level":"<?php echo $_smarty_tpl->tpl_vars['head']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['index'] : null)]['level'];?>
"
		}
<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_no']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_no']->value['last'] : null)) {
} else { ?>
		,
<?php }
}
}
?>
		]
<?php }
}
}
