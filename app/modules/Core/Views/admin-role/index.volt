{{ content() }}
<div class="box box-default">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <ul class="nav nav-pills">
                    <li>
                        <button onclick="location.href='{{ url("admin/core/role/create") }}'" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-plus"></i> New</button>
                    </li>
                </ul>
            </div>
            <div class="col-sm-offset-3 col-md-offset-3  col-sm-3 col-md-3">

            </div>
        </div>
    </div>

    <div class="box-body">
        {{ widget.render('GridView',
            [
                'columns': [
                    ['data': 'id', 'searchable': false],
                    ['data': 'name'],
                    ['data': 'parent_id'],
                    ['data': 'description']
                ],
                'url': url('admin/core/role/search'),
                'actions': [
                    'update': url('admin/core/role/edit'),
                    'delete': url('admin/core/role/delete')
                ],
                'tableId': 'table'
            ],
            ['cache': false]
        ) }}
    </div>
</div>