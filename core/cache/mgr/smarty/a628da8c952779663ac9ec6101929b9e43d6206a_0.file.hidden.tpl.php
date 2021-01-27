<?php
/* Smarty version 3.1.36, created on 2021-01-21 10:07:11
  from 'C:\xampp\htdocs\Project.Modx\manager\templates\default\element\tv\renders\input\hidden.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6009989f5b6e20_79924176',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a628da8c952779663ac9ec6101929b9e43d6206a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Project.Modx\\manager\\templates\\default\\element\\tv\\renders\\input\\hidden.tpl',
      1 => 1603378960,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6009989f5b6e20_79924176 (Smarty_Internal_Template $_smarty_tpl) {
?><input id="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" name="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" type="hidden" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->get('value'), ENT_QUOTES, 'UTF-8', true);?>
" />

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

MODx.on('ready',function() {
    var fld = MODx.load({
    
        xtype: 'hidden'
        ,applyTo: 'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,value: '<?php echo strtr($_smarty_tpl->tpl_vars['tv']->value->get('value'), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
'
    
    });
    var p = Ext.getCmp('modx-panel-resource');
    if (p) {
        p.add(fld);
        p.doLayout();
    }
});

// ]]>
<?php echo '</script'; ?>
>
<?php }
}
