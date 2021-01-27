<?php
/* Smarty version 3.1.36, created on 2021-01-21 10:07:11
  from 'C:\xampp\htdocs\Project.Modx\manager\templates\default\element\tv\renders\input\date.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6009989fdf3ee3_09840874',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1667e20521c5b44471569bc7de5e34f854c53f29' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Project.Modx\\manager\\templates\\default\\element\\tv\\renders\\input\\date.tpl',
      1 => 1603378960,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6009989fdf3ee3_09840874 (Smarty_Internal_Template $_smarty_tpl) {
?><input id="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" type="hidden" class="datefield"
    value="<?php echo $_smarty_tpl->tpl_vars['tv']->value->value;?>
" name="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
"
    onblur="MODx.fireResourceFormChange();"/>

<?php echo '<script'; ?>
 type="text/javascript">
// <![CDATA[

Ext.onReady(function() {
    var fld = MODx.load({
    
        xtype: 'xdatetime'
        ,applyTo: 'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,name: 'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,dateFormat: MODx.config.manager_date_format
        ,timeFormat: MODx.config.manager_time_format
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['disabledDays'])===null||$tmp==='' ? '' : $tmp)) {?>,disabledDays: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['disabledDays'])===null||$tmp==='' ? '' : $tmp);
}?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['minDateValue'])===null||$tmp==='' ? '' : $tmp)) {?>,minDateValue: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['minDateValue'])===null||$tmp==='' ? '' : $tmp);?>
'<?php }?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['maxDateValue'])===null||$tmp==='' ? '' : $tmp)) {?>,maxDateValue: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['maxDateValue'])===null||$tmp==='' ? '' : $tmp);?>
'<?php }?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['startDay'])===null||$tmp==='' ? '' : $tmp)) {?>,startDay: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['startDay'])===null||$tmp==='' ? '' : $tmp);
}?>

        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['minTimeValue'])===null||$tmp==='' ? '' : $tmp)) {?>,minTimeValue: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['minTimeValue'])===null||$tmp==='' ? '' : $tmp);?>
'<?php }?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['maxTimeValue'])===null||$tmp==='' ? '' : $tmp)) {?>,maxTimeValue: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['maxTimeValue'])===null||$tmp==='' ? '' : $tmp);?>
'<?php }?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['timeIncrement'])===null||$tmp==='' ? '' : $tmp)) {?>,timeIncrement: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['timeIncrement'])===null||$tmp==='' ? '' : $tmp);
}?>
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['hideTime'])===null||$tmp==='' ? '' : $tmp)) {?>,hideTime: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['params']->value['hideTime'])===null||$tmp==='' ? '' : $tmp);
}?>

        ,dateWidth: 198
        ,timeWidth: 198
        ,allowBlank: <?php if ($_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 1 || $_smarty_tpl->tpl_vars['params']->value['allowBlank'] == 'true') {?>true<?php } else { ?>false<?php }?>
        ,value: '<?php echo $_smarty_tpl->tpl_vars['tv']->value->value;?>
'
        ,msgTarget: 'under'
    
        ,listeners: { 'change': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});

// ]]>
<?php echo '</script'; ?>
><?php }
}
