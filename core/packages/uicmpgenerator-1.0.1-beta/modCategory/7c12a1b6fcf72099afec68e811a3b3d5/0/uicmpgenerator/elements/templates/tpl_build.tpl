<script id="tpl-table" type="x-tmpl-mustache">
       <div class="uicmpg-table js-table show-type show-fields {{used}}" data-id="{{name}}">
            <div class="uicmpg-table-title">{{name}}
                <div class="uicmpg-act">
                    <i class="icon icon-eye js-btn-type"></i>
                    <i class="icon icon-arrow js-btn-field"></i>
                </div>
            </div>
            {{{fields}}}
        </div>
</script>
<script id="tpl-table-field" type="x-tmpl-mustache">
       <div class="uicmpg-table-row">
            <div class="uicmpg-table-field {{key}}">{{field}}</div>
            <div class="uicmpg-table-type">{{type}}</div>
       </div>
</script>