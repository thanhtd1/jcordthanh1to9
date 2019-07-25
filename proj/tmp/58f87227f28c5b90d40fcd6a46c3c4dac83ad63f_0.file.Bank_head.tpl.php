<?php
/* Smarty version 3.1.33, created on 2019-07-22 18:49:59
  from 'D:\xampp\htdocs\jcordthanh1to9\proj\lib\templates\Bank_head.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d3586c7568be9_72224696',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '58f87227f28c5b90d40fcd6a46c3c4dac83ad63f' => 
    array (
      0 => 'D:\\xampp\\htdocs\\jcordthanh1to9\\proj\\lib\\templates\\Bank_head.tpl',
      1 => 1563423717,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d3586c7568be9_72224696 (Smarty_Internal_Template $_smarty_tpl) {
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
