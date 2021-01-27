draw2d.decoration.connection.ForkDecorator = draw2d.decoration.connection.BarDecorator.extend({

    NAME : "draw2d.decoration.connection.ForkDecorator",
    init: function(width, height)
    {
        this._super( width, height);
    },

    paint:function(paper)
    {
        var st = paper.set();

        st.push(paper.path(["M0 " + -this.height/2.5 ,
            "L", this.width/1.4, " ", 0,
            "L", 0, " ",  this.height/2.5,
            "M", this.width/1.4," " , -this.height/2,
            "L", this.width/1.4, " ", this.height/2
        ].join("")));
        return st;
    }

});

draw2d.decoration.connection.Bar2Decorator = draw2d.decoration.connection.BarDecorator.extend({

    NAME : "draw2d.decoration.connection.Bar2Decorator",

    init: function(width, height)
    {
        this._super( width, height);
    },

    paint:function(paper)
    {
        var st = paper.set();
        var path = ["M", this.width/2," " , -this.height/2];
        path.push(  "L", this.width/2, " ", this.height/2);
        path.push( "M", this.width/1.4," " , -this.height/2);
        path.push(  "L", this.width/1.4, " ", this.height/2);

        st.push(
            paper.path(path.join(""))
        );
        st.attr({fill:this.backgroundColor.hash(),stroke:this.color.hash()});
        return st;
    }

});

var UiCMPConnection = draw2d.Connection.extend({

    NAME : "UiCMPConnection",

    init:function(attr)
    {
      this._super(attr);
      this.setRouter(new draw2d.layout.connection.InteractiveManhattanConnectionRouter());
      this.setOutlineStroke(0);
      this.setOutlineColor("none");
      this.setStroke(1);
      this.setRadius(10);
      this.setColor('#3998c1');
      this.uicmpg = attr ? attr.uicmpg : {};
      this.setDecorators();
    }
    ,setDecorators:function()
    {
        if(this.uicmpg)
        {
            if(this.uicmpg.dependence == 1) {
                this.addCssClass('dotted-line');
            }
            if(this.uicmpg.relation == 1) {
                this.setTargetDecorator(new draw2d.decoration.connection.BarDecorator());
            } else {
                this.setTargetDecorator(new draw2d.decoration.connection.ArrowDecorator());
                //this.setTargetDecorator(new draw2d.decoration.connection.ForkDecorator());
            }
            this.setSourceDecorator(new draw2d.decoration.connection.Bar2Decorator());
        }
    }
    ,getPersistentAttributes : function()
    {
        var memento = this._super();
        var parentNode = this.getSource().getParent();
        while(parentNode.getParent()!==null){
            parentNode = parentNode.getParent();
        }
        memento.source.extend =   parentNode.extend;
        memento.source.node = parentNode.getName();
        memento.source.cls = parentNode.cls;
        var parentNode = this.getTarget().getParent();
        while(parentNode.getParent()!==null){
            parentNode = parentNode.getParent();
        }
        memento.target.extend =   parentNode.extend;
        memento.target.node = parentNode.getName();
        memento.target.cls =   parentNode.cls;
        memento.uicmpg = this.uicmpg;
        return memento;
    }
    ,setPersistentAttributes : function(memento)
    {
        this._super(memento);
        this.uicmpg = memento.uicmpg;
        this.setDecorators();
    },

    onDoubleClick: function(e){
      console.log('DoubleClick');
    }

});






