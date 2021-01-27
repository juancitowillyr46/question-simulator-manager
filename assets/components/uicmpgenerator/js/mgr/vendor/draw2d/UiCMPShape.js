var UiCMPShape = draw2d.shape.layout.VerticalLayout.extend({

    NAME: "UiCMPShape",

    init : function(attr)
    {
        var self = this;
        this.inputLocator  = new CollapsibleInputLocator();
        this.outputLocator = new CollapsibleOutputLocator();

        this._super($.extend({bgColor:"#f2f2f2", color:"#3998c1", stroke:1, radius:2, gap:1},attr));
        this.countField = 0;

        this.header = new draw2d.shape.layout.HorizontalLayout({
            stroke: 0,
            radius: 0,
            radius:this.getRadius(),
            bgColor: "#3998c1"
        });

        this.title = new draw2d.shape.basic.Label({
            text:'',
            fontColor:"#ffffff",
            stroke:0,
            bold: true,
            padding:{left:10, right:10, top:5, bottom:5 }
        });
        this.title.onDoubleClick=function(e)
        {
            if( self.extend) {
                var cmd = new draw2d.command.CommandOpenWin(self,'edit-table', this.getText());
                this.getCanvas().getCommandStack().execute(cmd);
            }
        };

        this.header.add(this.title);

        this.add(this.header);

        if(attr) {
            this.id = attr.title;
            this.setName(attr.title);
            this.extend = attr.extend ? attr.extend : '';
            this.extendCls = attr.extendCls ? attr.extendCls : '';
            this.cls = attr.cls ? attr.cls : '';
            this.setStyle();
             for(var key in attr.fields) {
                if(attr.fields.hasOwnProperty(key)) {
                    this.addField(attr.fields[key]);
                }
             }
        }
    }
    ,setStyle: function(){
        if(this.extend) {
            this.header.setBackgroundColor('#c1395f');
            this.setColor('#c1395f');
        }
    }
    ,setName: function(name)
    {
        var oldName = this.title.getText();
        this.title.setText(name);
        this.id = name;
        if(oldName != '' && oldName != name) {
           this.getCanvas().getCommandStack().execute(new draw2d.command.CommandChangedScheme(this));
        }
        return this;
    }
    ,getName:function(){
        return this.title.getText(); 
    }
    ,removeField: function(index)
    {
        this.remove(this.children.get(index+1).figure);
        this.countField --;
    }
    ,getField: function(index)
    {
        return this.children.get(index+1).figure;
    }
    ,addField: function(data, optionalIndex)
    {
        var bgColor =  this.countField % 2 ? '#fafafa' : '#ffffff';
        var row = new draw2d.shape.layout.HorizontalLayout({
            stroke: 0,
            radius: 0,
            bgColor: bgColor
        });
       var o = {text: data.field, fontColor:"#081922", resizeable:true, stroke:0, padding:{left:10, right:10, top:5, bottom:5 }};
       if(data.key) o.bold  = true;
        row.add(new draw2d.shape.basic.Label(o));
        row.createPort("input",  this.inputLocator).setName('input_' + data.field);
        row.createPort("output", this.outputLocator).setName('output_' + data.field);


        if($.isNumeric(optionalIndex))
        {
            this.add(row, null, optionalIndex+1);
        }
        else
        {
            this.add(row);
        }
        this.countField ++;
        return row;
    },
    setPersistentAttributes : function(memento)
    {
        this._super(memento);
        this.setName(memento.id);
        this.extend = memento.extend;
        this.extendCls = memento.extendCls;
        this.cls = memento.cls;
        if(typeof memento.fields !== "undefined"){
            $.each(memento.fields, $.proxy(function(i,e){
                this.addField(e);
            },this));
        }
        this.setStyle();
        return this;
    }
    ,getPersistentAttributes : function()
    {
        var memento= this._super();
        memento.id = this.title.getText();
        memento.fields   = [];
        memento.extend = this.extend;
        memento.extendCls = this.extendCls;
        memento.cls = this.cls;
        this.children.each(function(i,e){
            if(i>0){
                delete memento.ports;
                var label = e.figure.getChildren().get(0);
                memento.fields.push({
                    field:label.getText(),
                    id: e.figure.id,
                    key: label.isBold()
                });
            }
        });
        return memento;
    }


});