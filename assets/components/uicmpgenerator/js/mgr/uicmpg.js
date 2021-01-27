var Uicmpg = function(config) {
    config = config || {};
    Uicmpg.superclass.constructor.call(this,config);
};
Ext.extend(Uicmpg,Ext.Component,{
    page:{},
    window:{},
    grid:{},
    tree:{},
    panel:{},
    combo:{},
    config: {},
    view:{},
    util:{
        timestampFormat:function(format){
            format = format || 'd-m-Y';
            return function(v){
                return Ext.util.Format.date(new Date(v * 1000), format);
            };
        }
    }
});
Ext.reg('uicmpg',Uicmpg);
Uicmpg = new Uicmpg();
