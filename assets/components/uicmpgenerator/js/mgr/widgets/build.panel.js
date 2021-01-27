Uicmpg.panel.Build = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('uicmpgenerator.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('uicmpgenerator.tab_modeler')
                ,defaults: { autoHeight: true }
                ,items: [{
                    tbar: [{
                         text: _('uicmpgenerator.btn.build_package')
                        ,handler: this.buildPackage
                        ,scope: this
                        ,cls:'primary-button'
                    },{
                        text: _('uicmpgenerator.refresh')
                        ,handler: this.refreshPackage
                        ,scope: this
                        ,cls:''
                    }]},{
                    html: '<div id="uicmpg-layout-container" class="uicmpg-layout-container loading"><div class="ui-layout-center"><div class="uicmpg-scene js-scene"><div  onselectstart="javascript:/*IE8 hack*/return false" id="uicmpg-canvas"></div></div><div class="loader"></div></div><div class="ui-layout-south js-log"></div><div class="ui-layout-west js-layout-west"><div class="uicmpg-tool-bar"><div class="uicmpg-search"><input type="text" placeholder="'+_('uicmpgenerator.search')+'" class="js-search-q" /><i class="icon icon-search"></i></div><div class="uicmpg-button"><i class="icon icon-eye js-btn-type"></i><i class="icon icon-arrow js-btn-field"></i><i class="icon icon-sort js-btn-sort"></i></div></div><div class="js-table-box"></div></div></div>'
                }]
            },{
                title: _('uicmpgenerator.tab_scheme')
                ,defaults: {
                    autoHeight: true
                }
                ,items: [{
                    xtype: 'uicmpg-view-schema'
                    ,name: 'schema'
                    ,closable: true
                }]
                ,listeners: {
                    activate: function(tab) {
                        MODx.Ajax.request({
                            url: Uicmpg.config.connector_url
                            ,params: {
                                action: 'mgr/uicmpgenerator/getScheme'
                                ,id: Uicmpg.packageId
                            }
                            ,listeners: {
                                'success':{fn:function(data) {
                                   tab.items.items[0].setValue(data.message);
                                },scope:this}
                            }
                        });
                    }
                }
            }]

        }]

    });

    Uicmpg.panel.Build.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.panel.Build,MODx.FormPanel,{
    buildPackage: function() {
        MODx.Ajax.request({
            url: Uicmpg.config.connector_url
            ,params: {
                action: 'mgr/uicmpgenerator/buildPackage'
                ,id: Uicmpg.packageId
            }
            ,listeners: {
                'success':{fn:function(data) {
                    MODx.msg.alert(_('success'), data.message);
                },scope:this}
            }
        });
        //MODx.loadPage('security/user/create');
    }
    ,refreshPackage: function(){
        MODx.Ajax.request({
            url: Uicmpg.config.connector_url
            ,params: {
                action: 'mgr/uicmpgenerator/refreshPackage'
                ,id: Uicmpg.packageId
            }
            ,listeners: {
                'success':{fn:function(res) {
                    try {
                        var data =  Ext.decode(res.message);
                        Uicmpg.generator.refresh(data);
                    } catch(e) {
                        MODx.msg.alert(_('error'), e);
                    }
                },scope:this}
            }
        });

    }
});
Ext.reg('uicmpg-panel-build',Uicmpg.panel.Build);


Uicmpg.window.CreateConnection = function(config) {
    config = config || {};
    var self = this;
    Ext.applyIf(config,{
        title: _('uicmpgenerator.title.create_connection')
        ,width: 400
        ,autoHeight:true
        ,modal: true
        ,buttons: [{
            text: _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('done')
            ,scope: this
            ,handler: this.done
        }]
        ,fields: [{
                xtype: 'combo'
                ,fieldLabel: _('uicmpgenerator.label.relation_type')
                ,name: 'relation'
                ,hiddenName: 'relation'
                ,store: new Ext.data.SimpleStore({
                fields: ['type','disp']
                ,data: [[1,_('uicmpgenerator.options.relation1')]
                    ,[2,_('uicmpgenerator.options.relation2')]]
            })
                ,mode: 'local'
                ,triggerAction: 'all'
                ,displayField: 'disp'
                ,valueField: 'type'
                ,editable: false
                ,value: 1
                ,anchor: '100%'
            },{
            xtype: 'combo'
            ,fieldLabel: _('uicmpgenerator.label.dependence_type')
            ,name: 'dependence'
            ,hiddenName: 'dependence'
            ,store: new Ext.data.SimpleStore({
                fields: ['type','disp']
                ,data: [[1,_('uicmpgenerator.options.dependence1')]
                    ,[2,_('uicmpgenerator.options.dependence2')]]
            })
            ,mode: 'local'
            ,triggerAction: 'all'
            ,displayField: 'disp'
            ,valueField: 'type'
            ,editable: false
            ,value: 1
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.label.alias')
            ,name: 'source_alias'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.label.alias')
            ,name: 'target_alias'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: 'act'
            ,value: 'connection'

        }]
        ,open: function(data){
            var sourceAlias = this.fp.getForm().findField('source_alias')
                ,targetAlias = this.fp.getForm().findField('target_alias');
            sourceAlias.value = data.source_alias;
            sourceAlias.fieldLabel =  _('uicmpgenerator.label.alias') +  data.source_table;
            targetAlias.value = data.target_alias;
            targetAlias.fieldLabel =  _('uicmpgenerator.label.alias') +  data.target_table;
            this.show();
        }
        ,close: function(){

        }
    });
    Uicmpg.window.CreateConnection .superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.window.CreateConnection ,MODx.Window,{
    done: function() {
        this.fireEvent('connection', this.fp.getForm().getValues());
        this.hide();
    }
});
Ext.reg('uicmpg-window-create-connection',Uicmpg.window.CreateConnection );

Uicmpg.window.Extending = function(config) {
    config = config || {};
    var self = this;
    Ext.applyIf(config,{
        title: _('uicmpgenerator.title.extending')
        ,width: 400
        ,autoHeight:true
        ,modal: true
        ,buttons: [{
            text: _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('done')
            ,id:'extending-btn-done'
            ,scope: this
            ,handler: this.done
        }]
        ,fields: [{
            xtype: 'combo'
            ,fieldLabel: _('uicmpgenerator.label.extends')
            ,name: 'extends'
            ,hiddenName: 'extends'
            ,store: null
            ,mode: 'local'
            ,triggerAction: 'all'
            ,displayField: 'disp'
            ,valueField: 'ext'
            ,editable: false
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.label.extends_name')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'label'
            ,cls:'x-form-invalid-msg'
            ,hidden: true
            ,id:'extending-mess'
            ,text: ''

        },{
            xtype: 'hidden'
            ,name: 'act'
            ,value: 'extends'

        },{
            xtype: 'hidden'
            ,name: 'x'
            ,value: 0

        },{
            xtype: 'hidden'
            ,name: 'y'
            ,value: 0

        }]
        ,open: function(data){
            this.autoClose = data.autoClose == undefined ? true : false;
            this.fp.getForm().findField('x').value = data.x;
            this.fp.getForm().findField('y').value = data.y;
            var combo = this.fp.getForm().findField('extends');
            combo.store = new Ext.data.SimpleStore({
                fields: ['ext','disp']
                ,data: data.store
            })
            combo.setValue(combo.getStore().getAt(0).data.ext);
            this.show();
        }
        ,close: function(){
            this.hide();
        }
        ,setMess: function(t){
            var mess = Ext.getCmp('extending-mess');
            mess.setText(t);
            mess.show();
        }
    });
    Uicmpg.window.Extending.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.window.Extending ,MODx.Window,{
    done: function() {
        Ext.getCmp('extending-mess').hide();
        Ext.getCmp('extending-btn-done').disable();
        this.fireEvent('create', this.fp.getForm().getValues());
        if(this.autoClose) this.hide();
    }
});
Ext.reg('uicmpg-window-extending',Uicmpg.window.Extending);

Uicmpg.window.EditTableName = function(config) {
    config = config || {};
    var self = this;
    Ext.applyIf(config,{
        title: _('uicmpgenerator.title.edit_table_name')
        ,width: 400
        ,autoHeight:true
        ,modal: true
        ,buttons: [{
            text: _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('done')
            ,id:'edit-table-name-btn-done'
            ,scope: this
            ,handler: this.done
        }]
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.label.table_name')
            ,name: 'name'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: 'label'
            ,cls:'x-form-invalid-msg'
            ,hidden: true
            ,id:'extending-mess'
            ,text: ''

        },{
            xtype: 'hidden'
            ,name: 'act'
            ,value: 'edit-table'

        },{
            xtype: 'hidden'
            ,name: 'id'
            ,value: 0

        }]
        ,open: function(data){
            this.autoClose = data.autoClose == undefined ? true : false;
            this.fp.getForm().findField('name').value = data.name;
            this.fp.getForm().findField('id').value = data.id;
            this.show();
        }
        ,close: function(){
            this.hide();
        }
        ,setMess: function(t){
            var mess = Ext.getCmp('extending-mess');
            mess.setText(t);
            mess.show();
        }
    });
    Uicmpg.window.EditTableName.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.window.EditTableName ,MODx.Window,{
    done: function() {
        Ext.getCmp('extending-mess').hide();
        Ext.getCmp('edit-table-name-btn-done').disable();
        this.fireEvent('edit', this.fp.getForm().getValues());
        if(this.autoClose) this.hide();
    }
});
Ext.reg('uicmpg-window-edit-table-name',Uicmpg.window.EditTableName);