var UiCMPCanvas = draw2d.Canvas.extend({

        NAME: "UiCMPCanvas",

        init:function(id, w, h){
            var self = this;
            this._super(id, w, h);
            this.installEditPolicy( new draw2d.policy.canvas.SnapToGeometryEditPolicy());
            this.on("contextmenu", function(emitter, event){
               if(emitter.currentHoverFigure === null) {
                   $.contextMenu({
                       selector: 'body',
                       events: {
                           hide: function () {
                               $.contextMenu('destroy');
                           }
                       },
                       callback: $.proxy(function (key, options) {
                           switch (key) {
                               case "extending":
                                   emitter.getCommandStack().execute(new draw2d.command.CommandMenuExtending(emitter, event.x, event.y));
                                   break;
                               case "clear":
                                   self.clear();
                                   break;
                               default:
                                   break;
                           }
                       }, this),
                       x: event.x,
                       y: event.y,
                       items: {
                           "extending": {name:  _('uicmpgenerator.draw2d.menu_extends')},
                           "clear": {name: _('uicmpgenerator.draw2d.menu_clear_all')}
                       }
                   });
               }
            });
        }
});

//------------------------------------------------------------------------------

draw2d.command.CommandEditLable = draw2d.command.Command.extend({

    init: function(figure, text)
    {
        this._super("Edit Label");
        this.figure = figure;
        this.canvas =figure.getCanvas();
        this.newText = text;
        this.oldText = figure.getText();

    },
    execute:function()
    {
        this.figure.setText(this.newText);
    },

    redo:function()
    {
        this.execute();
    },
    undo:function()
    {
        this.figure.setText(this.oldText);
    }
});

draw2d.command.CommandMenuExtending = draw2d.command.Command.extend({

    init: function(figure,x,y)
    {
        this._super("Menu extending");
        this.figure = figure;
        this.canvas =figure;
        this.point = {x: x, y: y};
    },
    execute:function()
    {
    },

    redo:function()
    {
        this.execute();
    },
    undo:function()
    {
    }
});

draw2d.command.CommandChangedScheme = draw2d.command.Command.extend({

    init: function(figure)
    {
        this._super("Changed scheme");
        this.figure = figure;
        this.canvas = figure.getCanvas();
    },
    execute:function()
    {
    },

    redo:function()
    {
        this.execute();
    },
    undo:function()
    {
    }
});

draw2d.command.CommandOpenWin = draw2d.command.Command.extend({

    init: function(figure, act, data)
    {
        this._super("Win edit label");
        this.figure = figure;
        this.canvas = figure.getCanvas();
        this.data = data;
        this.act = act;
    },
    execute:function()
    {
    },

    redo:function()
    {
        this.execute();
    },
    undo:function()
    {
    }
});
