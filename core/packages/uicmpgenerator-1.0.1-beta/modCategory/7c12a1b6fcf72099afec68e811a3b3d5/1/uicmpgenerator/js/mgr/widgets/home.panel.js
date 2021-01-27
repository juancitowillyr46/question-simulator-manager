Uicmpg.panel.Home = function(config) {
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
                title: _('uicmpgenerator')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('uicmpgenerator.management_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'uicmpg-grid-home'
                    ,preventRender: true
                }]
            }]
        }]
    });
    Uicmpg.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.panel.Home,MODx.Panel);
Ext.reg('uicmpg-panel-home',Uicmpg.panel.Home);