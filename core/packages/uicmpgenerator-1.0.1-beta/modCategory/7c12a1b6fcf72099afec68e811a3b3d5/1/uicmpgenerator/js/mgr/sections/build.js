Ext.onReady(function() {
    MODx.load({ xtype: 'uicmpg-page-build'});
});

Uicmpg.page.Build = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'uicmpg-panel-build'
            ,renderTo: 'uicmpg-panel-build-div'
            ,package: MODx.request.package
        }]
    });
    Uicmpg.page.Build.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.page.Build,MODx.Component);
Ext.reg('uicmpg-page-build',Uicmpg.page.Build);

Ext.onReady(function() {
    Uicmpg.generator = new UiCMPGenerator({
            tables: Uicmpg.tables
            ,scheme: Uicmpg.scheme
            ,packageId: Uicmpg.packageId
            ,connectorUrl: Uicmpg.config.connector_url
    });
});