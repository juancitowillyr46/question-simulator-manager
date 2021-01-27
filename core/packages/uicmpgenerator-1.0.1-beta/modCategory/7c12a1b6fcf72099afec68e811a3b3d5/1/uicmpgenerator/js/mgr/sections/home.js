Ext.onReady(function() {
    MODx.load({ xtype: 'uicmpg-page-home'});
});

Uicmpg.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'uicmpg-panel-home'
            ,renderTo: 'uicmpg-panel-home-div'
        }]
    });
    Uicmpg.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg.page.Home,MODx.Component);
Ext.reg('uicmpg-page-home',Uicmpg.page.Home);

