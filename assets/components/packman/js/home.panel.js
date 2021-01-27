TP.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'tp-panel-home'
        ,url: TP.config.connector_url
        ,baseParams: {
            action: 'build'
        }
        ,border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,fileUpload: true
        ,items: [{
            html: '<h2>'+_('packman')+'</h2>'
            ,border: false
            ,id: 'tp-home-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,border: true
            ,defaults: { border: false ,autoHeight: true }
            ,items: [{
                title: _('packman.package')
                ,draggable: false
                ,layout: 'form'
                ,labelWidth: 200
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.intro_msg')
                    ,border: false
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('packman.package_name')
                    ,description: _('packman.package_name_desc')
                    ,name: 'category'
                    ,id: 'tp-category-name'
                    ,value: _('packman.mypackage')
                },{
                    xtype: 'textfield'
                    ,inputType: 'file'
                    ,name: 'readme'
                    ,fieldLabel: _('packman.readme')
                    ,description: _('packman.readme_desc')
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,inputType: 'file'
                    ,name: 'license'
                    ,fieldLabel: _('packman.license')
                    ,description: _('packman.license_desc')
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,inputType: 'file'
                    ,name: 'changelog'
                    ,fieldLabel: _('packman.changelog')
                    ,description: _('packman.changelog_desc')
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,name: 'version'
                    ,fieldLabel: _('packman.version')
                    ,description: _('packman.version_desc')
                    ,value: '1.0'
                },{
                    xtype: 'textfield'
                    ,name: 'release'
                    ,fieldLabel: _('packman.release')
                    ,description: _('packman.release_desc')
                    ,value: 'beta1'
                }]
            },{
                title: _('packman.templates')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.templates.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-templates'
                    ,id: 'tp-grid-templates'
                    ,preventRender: true
                }]
            },{
                title: _('packman.chunks')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.chunks.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-chunks'
                    ,id: 'tp-grid-chunks'
                    ,preventRender: true
                }]
            },{
                title: _('packman.snippets_custom')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.snippets.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-snippets'
                    ,id: 'tp-grid-snippets'
                    ,preventRender: true
                }]
            },{
                title: _('packman.plugins')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.plugins.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-plugins'
                    ,id: 'tp-grid-plugins'
                    ,preventRender: true
                }]
            },{
                title: _('packman.subpackages')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.subpackages.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-packages'
                    ,id: 'tp-grid-packages'
                    ,preventRender: true
                }]
            },{
                title: _('packman.directories')
                ,bodyStyle: 'padding: 15px;'
                ,items: [{
                    html: _('packman.directories.intro_msg')
                    ,border: false
                },{
                    xtype: 'tp-grid-directories'
                    ,id: 'tp-grid-directories'
                    ,preventRender: true
                }]
            }]
        }]
        ,listeners: {
            'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    TP.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(TP.panel.Home,MODx.FormPanel,{
    beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            templates: Ext.getCmp('tp-grid-templates').encode()
            ,chunks: Ext.getCmp('tp-grid-chunks').encode()
            ,snippets: Ext.getCmp('tp-grid-snippets').encode()
            ,plugins: Ext.getCmp('tp-grid-plugins').encode()
            ,packages: Ext.getCmp('tp-grid-packages').encode()
            ,directories: Ext.getCmp('tp-grid-directories').encode()
        });
    }
    ,success: function(o) {
        if (o.result.success) {
            var name = o.result.message;

            Ext.getCmp('tp-btn-export').setDisabled(false);
            Ext.getCmp('tp-grid-templates').getStore().commitChanges();
            Ext.getCmp('tp-grid-chunks').getStore().commitChanges();
            Ext.getCmp('tp-grid-snippets').getStore().commitChanges();
            Ext.getCmp('tp-grid-plugins').getStore().commitChanges();
            Ext.getCmp('tp-grid-packages').getStore().commitChanges();
            Ext.getCmp('tp-grid-directories').getStore().commitChanges();
            
            location.href = TP.config.connector_url+'?action=build&download='+name+'&HTTP_MODAUTH='+MODx.siteId;
        }
    }
});
Ext.reg('tp-panel-home',TP.panel.Home);