<?php
/* Smarty version 3.1.36, created on 2021-01-21 10:07:08
  from 'C:\xampp\htdocs\Project.Modx\core\components\migx\templates\mgr\gridpanel.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_6009989cb8a781_90917337',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '605beeae9504be25acc028913e7c4b14d1fd8ba6' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Project.Modx\\core\\components\\migx\\templates\\mgr\\gridpanel.tpl',
      1 => 1609647866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6009989cb8a781_90917337 (Smarty_Internal_Template $_smarty_tpl) {
?>

Migx.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-resource'
        ,components: [{
            xtype: 'modx-panel-resource'
            ,object_id: config.object_id
			,configs: config.configs
	        ,url: Migx.config.connector_url
        },{
            xtype: 'migx-panel-home'
            ,renderTo: 'migx-panel-home-div'
        }]
    });
    Migx.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Migx.page.Home,MODx.Component);
Ext.reg('migx-page-home',Migx.page.Home);


Migx.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+<?php echo $_smarty_tpl->tpl_vars['maincaption']->value;?>
+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [
            <?php echo $_smarty_tpl->tpl_vars['cmptabs']->value;?>

            ]
        }]
    });
    Migx.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Migx.panel.Home,MODx.Panel,{<?php echo $_smarty_tpl->tpl_vars['customHandlers']->value;?>
});
Ext.reg('migx-panel-home',Migx.panel.Home);


<?php echo $_smarty_tpl->tpl_vars['grids']->value;?>

<?php echo $_smarty_tpl->tpl_vars['updatewindows']->value;?>

<?php echo $_smarty_tpl->tpl_vars['iframewindows']->value;?>



Migx.panel.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
	    ,cls: 'container'
        ,url: config.url
        ,defaults: {
        	   collapsible: false 
                   ,autoHeight: true
        }
        ,baseParams: {configs: config.configs}		
        //,autoLoad: this.autoload(config)
        ,listeners: {
            'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
			,'load': {fn:this.load,scope:this}
        }		
    });
    Migx.panel.Object.superclass.constructor.call(this,config);
	//this.addEvents({ load: true });
};
Ext.extend(Migx.panel.Object,MODx.FormPanel,{
    autoload: function(config) {
		var a = {
            //url: MODx.config.manager_url+'index.php?a='+MODx.action['resource/tvs']
            url: config.url
			,method: 'GET'
            ,params: {
               //'a': MODx.action['resource/tvs']
                action: 'mgr/xdbedit/fields'
                ,object_id: config.object_id
				,configs: config.configs			   
               ,'class_key': 'modDocument'//config.class_key
            }
            ,scripts: true
            ,callback: function() {
                this.fireEvent('load');
                MODx.fireEvent('ready');
            }
            ,scope: this
        };
        return a;        	
    }
    
    ,
    setup: function() {

    }
    ,beforeSubmit: function(o) {
        if (typeof(tinyMCE) != 'undefined') {        
            tinyMCE.triggerSave();
        }     
    }
    ,success: function(o) {
        if (o.result.message != ''){
            Ext.Msg.alert(_('warning'), o.result.message);
        }
        this.doAutoLoad();
		var gf = Ext.getCmp('xdbedit-grid-objects');
		gf.isModified = true;
		gf.refresh();
     },
	 load: function() {
        //console.log('test');
		//MODx.loadRTE();
        if (typeof(Tiny) != 'undefined') {
		    var s={};
            if (Tiny.config){
                s = Tiny.config || {};
                delete s.assets_path;
                delete s.assets_url;
                delete s.core_path;
                delete s.css_path;
                delete s.editor;
                delete s.id;
                delete s.mode;
                delete s.path;
                s.cleanup_callback = "Tiny.onCleanup";
                var z = Ext.state.Manager.get(MODx.siteId + '-tiny');
                if (z !== false) {
                    delete s.elements;
                }			
		    }
			s.mode = "specific_textareas";
            s.editor_selector = "modx-richtext";
		    //s.language = "en";// de seems not to work at the moment
            tinyMCE.init(s);				
		}        
	  
	 }
		
    
});
Ext.reg('modx-panel-resource',Migx.panel.Object);

MODx.fireResourceFormChange = function(f,nv,ov) {
    //Ext.getCmp('modx-panel-resource').fireEvent('fieldChange');
};

<?php }
}
