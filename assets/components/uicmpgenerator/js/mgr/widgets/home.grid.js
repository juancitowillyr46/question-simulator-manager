Uicmpg.grid.UicmpgGenerator = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'uicmpg-grid-home'
        ,url: Uicmpg.config.connectorUrl
        ,baseParams: { action: 'mgr/uicmpgenerator/getList' }
        ,save_action: 'mgr/uicmpgenerator/updateFromGrid'
        ,fields: ['id','package', 'database', 'table_prefix','build_scheme', 'build_package','create_date','last_ran']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'tables'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('uicmpgenerator.package')
            ,dataIndex: 'package'
            ,sortable: true
            ,width: 40
            ,editor: { xtype: 'textfield' }
        },{
            header: _('uicmpgenerator.database')
            ,dataIndex: 'database'
            ,sortable: true
            ,width: 40
            ,editor: { xtype: 'textfield' }
        },{
            header: _('uicmpgenerator.table_prefix')
            ,dataIndex: 'table_prefix'
            ,sortable: true
            ,width: 20
            ,editor: { xtype: 'textfield' }
        },{
            header: _('uicmpgenerator.create_date')
            ,dataIndex: 'create_date'
            ,sortable: true
            ,width: 40
        },{
            header: _('uicmpgenerator.last_ran')
            ,dataIndex: 'last_ran'
            ,sortable: true
            ,width: 40
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'uicmpg-search-filter'
            ,emptyText: _('uicmpgenerator.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            text: _('uicmpgenerator.create')
            ,handler: { xtype: 'uicmpg-window-uicmpgenerator-build' ,blankValues: true }
        }]
    });

    Uicmpg.grid.UicmpgGenerator.superclass.constructor.call(this,config);

};

Ext.extend(Uicmpg.grid.UicmpgGenerator,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {

        var m = [{
            text: _('uicmpgenerator.edit_package')
            ,handler: this.build
        },'-',{
            text: _('uicmpgenerator.remove')
            ,handler: this.removeUicmpg
        }];
        this.addContextMenuItem(m);

        return true;
    }
    ,build: function(btn,e) {
        var id = this.menu.record.id;
        location.href = '?a=build&namespace=uicmpgenerator&package='+id;
    }

    ,removeUicmpg: function() {
        MODx.msg.confirm({
            title: _('uicmpgenerator.remove')
            ,text: _('uicmpgenerator.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/uicmpgenerator/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    },
    renderYNfield: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        v = Ext.util.Format.htmlEncode(v);
        var f = MODx.grid.Grid.prototype.rendYesNo;
        return f(v,md,rec,ri,ci,s,g);
    }
});
Ext.reg('uicmpg-grid-home',Uicmpg.grid.UicmpgGenerator);

Uicmpg.window.BuildUicmpg = function(config) {
    //console.log('Update');
    config = config || {};
    Ext.applyIf(config,{
        title: _('uicmpgenerator.build')
        ,url: Uicmpg.config.connectorUrl
        ,baseParams: {
            action: 'mgr/uicmpgenerator/build'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.package')
            ,name: 'package'
            ,width: 300
            ,disable: true
            ,editable: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.database')
            ,name: 'database'
            ,width: 300
            ,disable: true
            ,editable: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uicmpgenerator.table_prefix')
            ,name: 'table_prefix'
            ,width: 150
            ,readonly: true
            ,disable: true
            ,editable: false
        }]
    });
    Uicmpg.window.BuildUicmpg.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.window.BuildUicmpg,MODx.Window);
Ext.reg('uicmpg-window-uicmpgenerator-build',Uicmpg.window.BuildUicmpg);
