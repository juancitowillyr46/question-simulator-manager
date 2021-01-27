
TP.grid.Packages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'tp-grid-packages'
        ,url: TP.config.connector_url
        ,baseParams: {
            action: 'package/getList'
        }
        ,action: 'package/getList'
        ,fields: ['signature']
        ,data: []
        ,autoHeight: true
        ,columns: [{
            header: _('packman.signature')
            ,dataIndex: 'signature'
            ,width: 250
        }]
        ,tbar: [{
            text: _('packman.subpackage_add')
            ,handler: this.addPackage
            ,scope: this
        }]
    });
    TP.grid.Packages.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create([{name: 'id'},{name:'signature'}]);
};
Ext.extend(TP.grid.Packages,TP.grid.LocalGrid,{
    getMenu: function() {
        return [{
            text: _('packman.subpackage_remove')
            ,handler: this.remove.createDelegate(this,[{
                title: _('packman.subpackage_remove')
                ,text: _('packman.subpackage_remove_confirm')
            }])
            ,scope: this
        }];
    }
    ,addPackage: function(btn,e) {
        var r = {};
        
        if (!this.windows.addPackage) {
            this.windows.addPackage = MODx.load({
                xtype: 'tp-window-package-add'
                ,record: r
                ,listeners: {
                    'success': {fn:function(vs) {
                        var rec = new this.propRecord(vs);
                        this.getStore().add(rec);
                    },scope:this}
                }
            });
        }
        this.windows.addPackage.setValues(r);
        this.windows.addPackage.show(e.target);
    }
});
Ext.reg('tp-grid-packages',TP.grid.Packages);


TP.window.AddPackage = function(config) {
    config = config || {};
    this.ident = config.ident || 'tpapack'+Ext.id();
    Ext.applyIf(config,{
        title: _('packman.subpackage_add')
        ,frame: true
        ,id: 'tp-window-package-add'
        ,fields: [{
            xtype: 'tp-combo-package'
            ,fieldLabel: _('packman.subpackage')
            ,description: _('packman.subpackage_desc')
            ,name: 'package'
            ,hiddenName: 'package'
            ,id: 'tp-'+this.ident+'-package'
            ,allowBlank: false
            ,pageSize: 20
        }]
    });
    TP.window.AddPackage.superclass.constructor.call(this,config);
};
Ext.extend(TP.window.AddPackage,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var fld = f.findField('package');

        if (id != '' && this.fp.getForm().isValid()) {
            if (this.fireEvent('success',{
                signature: fld.getValue()
            })) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        } else {
            MODx.msg.alert(_('error'),_('packman.subpackage_err_ns'));
        }
    }
});
Ext.reg('tp-window-package-add',TP.window.AddPackage);

TP.combo.Package = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'package'
        ,hiddenName: 'package'
        ,displayField: 'signature'
        ,valueField: 'signature'
        ,fields: ['signature']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connector_url ? MODx.config.connector_url : MODx.config.connectors_url+'workspace/packages.php'
        ,baseParams: {
            action: MODx.config.connector_url ? 'workspace/packages/getList' : 'getList'
        }
    });
    TP.combo.Package.superclass.constructor.call(this,config);
};
Ext.extend(TP.combo.Package,MODx.combo.ComboBox);
Ext.reg('tp-combo-package',TP.combo.Package);