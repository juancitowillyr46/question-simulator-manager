<?php
/* Smarty version 3.1.36, created on 2021-01-21 10:07:12
  from 'C:\xampp\htdocs\Project.Modx\core\components\migx\elements\tv\migxdb.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_600998a05c0465_64970224',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '494f062736bfefdfc67de50f5b1d53a1df4af33b' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Project.Modx\\core\\components\\migx\\elements\\tv\\migxdb.tpl',
      1 => 1609647866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_600998a05c0465_64970224 (Smarty_Internal_Template $_smarty_tpl) {
?><input id="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" name="tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" type="hidden" class="textfield" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tv']->value->get('value'), ENT_QUOTES, 'UTF-8', true);?>
"<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
 tvtype="<?php echo $_smarty_tpl->tpl_vars['tv']->value->type;?>
" />
<div id="tvpanel<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
" style="width:100%">
</div>

<br/>

<?php echo '<script'; ?>
 type="text/javascript">
    // <![CDATA[
    <?php echo $_smarty_tpl->tpl_vars['grid']->value;?>

    
    <?php echo $_smarty_tpl->tpl_vars['updatewindow']->value;?>

    
    <?php echo $_smarty_tpl->tpl_vars['iframewindow']->value;?>


    



/*
Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {
     onRender : function(ct, position){
          this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});
     }
});
*/
/*
var MiPreviewPanel = new Ext.Panel({
     id: 'MiPreviewPanel',
     title: 'MIGX - Preview',
     closable:true,
     // layout to fit child component
     layout:'fit', 
     // add iframe as the child component
     items: [ new Ext.ux.IFrameComponent({ id: id, url: 'http://www.gitrevo.webcmsolutions.de/manager' }) ]
});
*/
/*
Ext.ux.IFrameComponent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout:'fit'
        ,id: 'modx-iframe-mi-preview'
        ,url: 'http://www.gitrevo.webcmsolutions.de/preview1.html' 
    });
    Ext.ux.IFrameComponent.superclass.constructor.call(this,config);
};
Ext.extend(Ext.ux.IFrameComponent,Ext.BoxComponent,{
     onRender : function(ct, position){
          this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});
     }
});
Ext.reg('modx-iframe-mi-preview',Ext.ux.IFrameComponent);
*/     


MODx.loadMIGXdbGridButton = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        handler: function() { this.loadGrid(); }
    });
    MODx.loadMIGXdbGridButton.superclass.constructor.call(this,config);
    this.options = config;
    this.config = config;
};

Ext.extend(MODx.loadMIGXdbGridButton,Ext.Button,{

    loadGrid: function(init) {
	    var resource_id = '<?php echo $_smarty_tpl->tpl_vars['resource']->value['id'];?>
';
        var object_id = '<?php echo $_smarty_tpl->tpl_vars['object_id']->value;?>
';
        
        if ('<?php echo (($tmp = @$_smarty_tpl->tpl_vars['customconfigs']->value['check_resid'])===null||$tmp==='' ? '' : $tmp);?>
' == '1'){
        if (object_id != ''){
            if (object_id == 'new'){
                if (!init){
                    alert (_('migx.save_object'));
                }
                
                return;
            }
        }        
        else{
            if (resource_id == 0){
                if (!init){
                    alert (_('migx.save_resource'));
                }
                return;
            }            
        }
        }        

        MODx.load({
            xtype: 'modx-grid-multitvdbgrid-<?php echo $_smarty_tpl->tpl_vars['win_id']->value;?>
'
            ,renderTo: 'tvpanel<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
            ,tv: '<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
            ,cls:'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
_items'
            ,id:'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
_items'
			,columns:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['columns']->value;?>
')
			,pathconfigs:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['pathconfigs']->value;?>
')
            ,fields:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['fields']->value;?>
')
            ,wctx: '<?php echo $_smarty_tpl->tpl_vars['myctx']->value;?>
'
            ,url: '<?php echo $_smarty_tpl->tpl_vars['config']->value['connectorUrl'];?>
'
            ,tv_type: '<?php echo $_smarty_tpl->tpl_vars['tv_type']->value;?>
'
            ,configs: '<?php echo $_smarty_tpl->tpl_vars['properties']->value['configs'];?>
'
            ,auth: '<?php echo $_smarty_tpl->tpl_vars['auth']->value;?>
'
            ,resource_id: '<?php echo $_smarty_tpl->tpl_vars['resource']->value['id'];?>
' 
            ,co_id: '<?php echo $_smarty_tpl->tpl_vars['connected_object_id']->value;?>
' 
            ,pageSize: 10
            ,object_id : '<?php echo $_smarty_tpl->tpl_vars['object_id']->value;?>
' 		
        });
        this.hide();
    }	

});
Ext.reg('modx-button-load-migxdb-grid',MODx.loadMIGXdbGridButton);


//load migx-lang into modx-lang
Ext.onReady(function() {
var lang = <?php echo $_smarty_tpl->tpl_vars['migx_lang']->value;?>
;
for (var name in lang) {
    MODx.lang[name] = lang[name];
}
  
});

loadGridButton = MODx.load({
        xtype: 'modx-button-load-migxdb-grid'
        ,renderTo: 'tvpanel<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
        ,text: '<?php echo $_smarty_tpl->tpl_vars['i18n_migx_loadgrid']->value;?>
'
});   

if ('<?php echo $_smarty_tpl->tpl_vars['customconfigs']->value['gridload_mode'];?>
' == '2'){
    loadGridButton.loadGrid(true);
}


        /*
        MODx.load({
            xtype: 'modx-grid-multitvdbgrid'
            ,renderTo: 'tvpanel<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
            ,tv: '<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
'
            ,cls:'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
_items'
            ,id:'tv<?php echo $_smarty_tpl->tpl_vars['tv']->value->id;?>
_items'
			,columns:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['columns']->value;?>
')
			,pathconfigs:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['pathconfigs']->value;?>
')
            ,fields:Ext.util.JSON.decode('<?php echo $_smarty_tpl->tpl_vars['fields']->value;?>
')
            ,wctx: '<?php echo $_smarty_tpl->tpl_vars['myctx']->value;?>
'
            ,url: '<?php echo $_smarty_tpl->tpl_vars['config']->value['connectorUrl'];?>
'
            ,configs: '<?php echo $_smarty_tpl->tpl_vars['properties']->value['configs'];?>
'
            ,auth: '<?php echo $_smarty_tpl->tpl_vars['auth']->value;?>
'
            ,resource_id: '<?php echo $_smarty_tpl->tpl_vars['resource']->value['id'];?>
' 
            ,pageSize: 10			
        });
        */



<?php echo '</script'; ?>
><?php }
}
