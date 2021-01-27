var UiCMPGenerator = function(options){
    var self = this
        ,defaults = {
            tables: {}
            ,scheme: []
            ,packageId: 0
            ,connectorUrl: ''
            ,container: '#uicmpg-layout-container'
        }
        ,tplTable
        ,tplTableField
        ,layout
        ,canvas
        ,reader
        ,callback = $.noop
        ,tablesScheme = {}
        ,isRefresh = false
        ,hasTableInScheme = function(title){
            return tablesScheme[title] ? true : false;
        }
        ,hasTable = function(table){
            return options.tables[table] ? true : false;
        }
        ,hasFieldInTable = function(table, field){
            return options.tables[table]['fields'][field] ? true : false;
        }
        ,hasFieldInScheme = function(field, fields){
            for(var key in fields) {
                if(fields[key].field === field)
                    return true;
            }
            return false;
        }
        ,getTableFromScheme = function(){
            var tables = {};
            if(options.scheme) {
                for (var key in options.scheme) {
                    if (options.scheme[key].type === 'UiCMPShape') {
                        tables[options.scheme[key].id] = options.scheme[key];
                    }
                }
            }
            return tables;
        }
        ,renderTables = function(){
            var out = '';
            for(var i in options.tables) {
                var outFields = '';
                for(var key in options.tables[i]['fields']) {
                    var fields = options.tables[i]['fields'][key];
                    fields.key = fields.key ? 'key' : '';
                    outFields += Mustache.render(tplTableField, fields);
                }
                out += Mustache.render(tplTable, {
                    name: i
                    ,fields: outFields
                    ,used: hasTableInScheme(i) ? 'uicmpg-table-used' : ''

                });
            }
            self._tableBox.html((out));
        }
        ,clearTables = function(){
            self._tableBox.html('');
        }
        ,clearFilterTables = function(){
            $('.uicmpg-table-hide', self._tableBox).removeClass('uicmpg-table-hide');
        }
        ,filterTables = function(val){
            if(val) {
                $('.uicmpg-table', self._tableBox).each(function() {
                    var $this = $(this)
                        ,id = $this.attr('data-id');
                    if (id && id.toString().toUpperCase().indexOf(val.toUpperCase()) == -1) {
                            $this.addClass('uicmpg-table-hide');
                    } else {
                        $this.removeClass('uicmpg-table-hide');
                    }
                });
            } else {
               clearFilterTables();
            }
        }
        ,onSwitchAllTablesFields = function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('hide')) {
                $this.removeClass('hide');
                $('.uicmpg-table', self._tableBox).addClass('show-fields');
            } else {
                $this.addClass('hide');
                $('.uicmpg-table', self._tableBox).removeClass('show-fields');
            }
        }
        ,onSwitchAllTablesFieldType = function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('hide')) {
                $this.removeClass('hide');
                $('.uicmpg-table', self._tableBox).addClass('show-type');
            } else {
                $this.addClass('hide');
                $('.uicmpg-table', self._tableBox).removeClass('show-type');
            }
        }
        ,onSwitchTableFields = function(e){
            e.preventDefault();
            var $table = $(this).closest('.js-table');
            if($table.hasClass('show-fields')) {
                $table.removeClass('show-fields');
            } else {
                $table.addClass('show-fields');
            }
        }
        ,onSwitchTableFieldType = function(e){
            e.preventDefault();
            var $table = $(this).closest('.js-table');
            if($table.hasClass('show-type')) {
                $table.removeClass('show-type');
            } else {
                $table.addClass('show-type');
            }
        }
        ,onSortTables = function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('desc')) {
                $this.removeClass('desc');
                $('.uicmpg-table', self._tableBox).tsort({order:'asc',attr:'data-id'});
            } else {
                $this.addClass('desc');
                $('.uicmpg-table', self._tableBox).tsort({order:'desc',attr:'data-id'});
            }
        }
        ,onSearch = function(e){
            switch(e.which) {
                case 13:  // enter
                case 27:  // escape
                case 38:  // стрелка вверх
                case 40:  // стрелка вниз
                    break;
                default:
                    var val = $.trim(self._search.val())
                    clearTimeout(self._search.timer);
                    self._search.timer = setTimeout(function() {
                        filterTables(val);
                    }, 500);
            }
        }
        ,onDrop = function(e, ui){
            ui.draggable.addClass('uicmpg-table-used');
            var offset = self._scene.offset()
                ,x = ui.offset.left - offset.left
                ,y =  ui.offset.top - offset.top
                ,title = ui.draggable.attr('data-id')
                ,figure = new UiCMPShape({x:x , y:y, title:title, fields:options.tables[title].fields, cls:options.tables[title].cls});
            canvas.add( figure);
            saveScheme();
        }
        ,submitForm = function(data){
            switch (data.act) {
                case 'connection':
                        callback(new UiCMPConnection({
                            uicmpg: {
                                relation: data.relation
                                ,dependence: data.dependence
                                ,alias_source: data.source_alias
                                ,alias_target: data.target_alias
                            }
                        }));
                        saveScheme();
                        return true;
                    break;
                case 'extends':
                    if(data.name) {
                        if(!hasTable(data.name)) {
                            canvas.add(new UiCMPShape({
                                x: data.x,
                                y: data.y,
                                title: data.name,
                                fields: options.tables[data.extends].fields,
                                extend: data.extends,
                                extendCls: options.tables[data.extends].cls
                            }));
                            saveScheme();
                            return true;
                        } else {
                            return _('uicmpgenerator.err_ae_table');
                        }
                    }
                    break;
                case 'edit-table':
                    if(data.name) {
                        if(!hasTable(data.name)){
                            var table = canvas.getFigure(data.id);
                            if (table) {
                                table.setName(data.name);
                            }
                            return true;
                        } else {
                            return _('uicmpgenerator.err_ae_table');
                        }
                    }
                    break;
            }
        }
        ,saveScheme = function(){
            var writer = new draw2d.io.json.Writer();
            writer.marshal(canvas,function(json){
                MODx.Ajax.request({
                    url: options.connectorUrl
                    ,params: {
                        action: 'mgr/uicmpgenerator/updateScheme'
                        ,id: options.packageId
                        ,scheme: JSON.stringify(json, null, 2)
                    }
                    ,listeners: {
                        'success':{fn:function() {
                        },scope:this}
                    }
                });
            });

        }
        ,check = function(){
            var isEdited = false;
            if(options.scheme) {
                for (var key in options.scheme) {
                    if(options.scheme[key].type === 'UiCMPShape') {
                        var table = options.scheme[key].extend ?  options.scheme[key].extend : options.scheme[key].id;
                        if(hasTable(table)) {
                            var schemeFields = options.scheme[key].fields;
                            for(var i in schemeFields) {
                                if($.isPlainObject(schemeFields[i])) {
                                    if (!hasFieldInTable(table, schemeFields[i].field)) {
                                        log(Mustache.render(_('uicmpgenerator.check.mess_nf_field'), {field:schemeFields[i].field, table:table }));
                                        schemeFields.splice(i, 1);
                                        isEdited = true;
                                    }
                                }
                            }
                            var n = 0;
                            for (var j in options.tables[table]['fields']) {
                                if (!hasFieldInScheme(j, schemeFields)) {
                                    schemeFields.splice(n, 0, {
                                        "field": j,
                                        "id":  draw2d.util.UUID.create(),
                                        "key": options.tables[table]['fields'][j].key
                                    });
                                    log(Mustache.render(_('uicmpgenerator.check.mess_add_field'), {table:table,field:j }), 'success');
                                    isEdited = true;
                                }
                                n++;
                            }

                        } else {
                            log(_('uicmpgenerator.check.mess_nf_table')  + table);
                            options.scheme.splice(key, 1);
                        }
                    } else if(options.scheme[key].type ==='UiCMPConnection') {
                        var isRemove = false
                            ,l = options.scheme[key]
                            ,sourcePort = removePortPrefix(l.source.port)
                            ,targetPort = removePortPrefix(l.target.port)
                            ,sourceNode = l.source.extend ? l.source.extend : l.source.node
                            ,targetNode = l.target.extend ? l.target.extend : l.target.node;
                            if(!hasTable(sourceNode)) {
                                isRemove = true;
                                log(Mustache.render(_('uicmpgenerator.check.mess_dependence_nf_table'), {table1:l.source.node,table2:l.target.node, field1:sourcePort, field2:targetPort }));
                            } else if(!hasTable(targetNode)){
                                isRemove = true;
                                log(Mustache.render(_('uicmpgenerator.check.mess_dependence_nf_table'), {table2:l.source.node,table1:l.target.node, field1:sourcePort, field2:targetPort }));
                            } else if(!hasFieldInTable(sourceNode, sourcePort)) {
                                isRemove = true;
                                log(Mustache.render(_('uicmpgenerator.check.mess_dependence_nf_field'), {table1:l.source.node,table2:l.target.node, field1:sourcePort, field2:targetPort }));
                            }  else if(!hasFieldInTable(targetNode, targetPort)) {
                                isRemove = true;
                                log(Mustache.render(_('uicmpgenerator.check.mess_dependence_nf_field'), {table1:l.source.node,table2:l.target.node, field2:sourcePort, field1:targetPort }));
                            }
                            if(isRemove) {
                                isEdited = true;
                                options.scheme.splice(key, 1);
                            }
                    }
                }
            }
            return isEdited;
        }
        ,removePortPrefix = function(str) {
           return  str.replace(/^(input_|output_)/g, '');
        }
        ,log = function(t, cls){
            cls = cls || 'error';
            var date = new Date()
                ,h = date.getHours()
                ,m = date.getMinutes()
                ,s = date.getSeconds()
                ,time =  (h < 10 ? '0' + h : h)   + ':' + (m < 10 ? '0' + m : m)  + ':' + (s < 10 ? '0' + s : s);
            self._log.append('<div class="uicmpg-log ' + cls + '"><span>' + time + '</span>' + t + '</div>');
            if(layout && !layout.south.isOpen) {
                layout.open('south');
            }
        }
        ,createAlias = function(str){
             str = str || '';
             var i=0
                 ,out = ''
                 ,arr = str.split('_');
             for(i=0; i < arr.length; i++) {
                 out +=  arr[i].slice(0, 1).toUpperCase() + arr[i].slice(1);
             }
             return out;
        }
        ,resizeCanvas = function(){
          //  canvas.setDimension(new draw2d.geo.Rectangle( 0, 0, self._scene.width(), self._scene.height() ));
        }
        ,setup = function(){
            self._container.addClass('loading');
            tablesScheme = getTableFromScheme();
            renderTables();
            if(options.scheme) {
                setTimeout(function(){
                    var isEdited = check();
                    reader.unmarshal(canvas, options.scheme);
                    self._container.removeClass('loading');
                    if(isEdited) saveScheme();
                },1000);
            }
        }
        ,init = function() {
            options = $.extend(defaults, options);
            layout = $("#uicmpg-layout-container").layout({
                west__size: 200
                ,stateManagement__enabled:	true
                ,west: {
                    minSize: 310
                }
                ,south: {
                    size: 120
                    ,initClosed: true
                }
                ,onresize: resizeCanvas
            });
            self._container = $(options.container);
            self._layoutWest = $('.js-layout-west', options.container);
            self._log =  $('.js-log', options.container);
            self._container = $(options.container);
            self._search= $(".js-search-q",self._layoutWest).on('keyup', onSearch);
            self._tableBox = $('.js-table-box', options.container)
                .on('click', '.js-btn-type', onSwitchTableFieldType)
                .on('click', '.js-btn-field', onSwitchTableFields);
            self._scene = $('.js-scene',options.container)
                .droppable({
                    tolerance: 'fit'
                    ,hoverClass: 'activate'
                    ,drop: onDrop
                });
            $(".js-btn-field",self._layoutWest).on('click', onSwitchAllTablesFields);
            $(".js-btn-type",self._layoutWest).on('click', onSwitchAllTablesFieldType);
            $(".js-btn-sort",self._layoutWest).on('click', onSortTables);
            tplTable =  $('#tpl-table').html();
            tplTableField = $('#tpl-table-field').html();
            Mustache.parse(tplTable);
            Mustache.parse(tplTableField);
            reader = new draw2d.io.json.Reader();
            canvas = new UiCMPCanvas('uicmpg-canvas', 3000, 3000);
            canvas.getCommandStack().addEventListener(function(e){
                if(e.isPostChangeEvent()){
                    if(e.getCommand() instanceof draw2d.command.CommandMenuExtending) {
                         var store = []
                             ,winExtending = MODx.load({xtype: 'uicmpg-window-extending'});
                        for(var i in options.tables) {
                            store.push([i,i]);
                        }
                        winExtending.on('create', function(data){
                            var result = submitForm(data);
                            if(result === true) {
                                winExtending.close();
                            } else {
                                winExtending.setMess(result);
                            }
                        }, this);
                        winExtending.open({store:store,x:e.command.point.x, y: e.command.point.y, autoClose:false});

                    } else if(e.getCommand() instanceof draw2d.command.CommandOpenWin) {
                          switch (e.command.act){
                              case 'edit-table':
                                  var winEditTableName = MODx.load({xtype: 'uicmpg-window-edit-table-name'});
                                  winEditTableName.on('edit', function(data){
                                      var result = submitForm(data);
                                      if(result === true) {
                                          winEditTableName.close();
                                      } else {
                                          winEditTableName.setMess(result);
                                      }
                                  }, this);
                                  winEditTableName.open({name:e.command.data, id:e.command.figure.id, autoClose:false});
                                  break;
                          }
                    } else {
                        if(e.getCommand() instanceof draw2d.command.CommandDelete && e.getCommand().figure instanceof UiCMPShape) {
                            $('.uicmpg-table[data-id="' + e.getCommand().figure.getId() + '"]', self._tableBox).removeClass('uicmpg-table-used');
                        }
                        if(!isRefresh) saveScheme();
                    }
                }

            });
            setup();
            draw2d.Connection.createConnection=function(sourcePort, targetPort, cb, dropTarget){
                callback = cb;
                var sourceTable = sourcePort.getParent().getParent()
                    ,targetTable = targetPort.getParent().getParent()
                    ,winConnection = MODx.load({xtype: 'uicmpg-window-create-connection'});
                winConnection.on('connection', submitForm, this);
                winConnection.open({
                    source_table: sourceTable.id
                    ,target_table: targetTable.id
                    ,source_alias: createAlias(targetTable.id)
                    ,target_alias: createAlias(sourceTable.id)
                });
            };
            $(".uicmpg-table" ).draggable({
                handle: ".uicmpg-table-title"
                ,appendTo: "body"
                ,helper: "clone"
                ,cursorAt: {
                    left: 0
                }
                ,start: function(event, ui){
                    ui.helper.width($(this).width());
                }
            });
            draw2d.Configuration.i18n.menu = {
                deleteSegment :  _('uicmpgenerator.draw2d.menu_delete_segment'),
                addSegment : _('uicmpgenerator.draw2d.menu_add_segment')
            };
        };
        this.refresh = function(data){
            if(data) {
                isRefresh = true;
                clearTables();
                canvas.clear();
                isRefresh = false;
                options.tables = data.tables;
                options.scheme = data.scheme;
                setup();

            }
        }
        init();
};