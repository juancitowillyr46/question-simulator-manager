Uicmpg.view.Schema =  Ext.extend(Ext.form.TextField,  {
    id: 'uicmpg-view-schema'
    ,fieldLabel: 'Label'
    ,hideLabel: true
    ,initEvents : function(){
        Uicmpg.view.Schema.superclass.initEvents.call(this);
        this.editor.on('focus', this.onFocus.bind(this));
        this.editor.on('blur', this.onBlur.bind(this));
    }
    ,initComponent : function(){
      Uicmpg.view.Schema.superclass.initComponent.call(this);
    }
    ,onRender : function(ct, position){
        Uicmpg.view.Schema.superclass.onRender.call(this, ct, position);
        this.editor =  CodeMirror.fromTextArea(this.el.dom, {
            lineNumbers: true
            ,styleActiveLine: true
            ,lineWrapping: true
            ,matchBrackets: true
            ,viewportMargin: Infinity
            ,mode: 'application/xml'
            ,theme: 'neo'
            ,indentUnit: 4
            ,extraKeys: {
                'F11': function (cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                }
                ,'Esc': function (cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });

    }
    ,onDestroy : function(){
        this.editor.destroy();
        Uicmpg.view.Schema.superclass.onDestroy.call(this);
    }
    ,getValue : function (){
        return this.value;
    }
    ,setValue : function (value){
        if (this.editor) {
            this.editor.setValue(value);
        }
        this.value = value;
    }
    ,focus: function (){
        if(this.editor) this.editor.focus();
    }
    ,blur: function (){
        this.editor.blur();
    }
});

Ext.reg('uicmpg-view-schema',Uicmpg.view.Schema);