<x-master-layout>

    <div class="main-content">
        <div class="title">
            Master data
        </div>
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h4>Tag</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @can('create master-data/tags')
                                <a class="mb-3 btn btn-primary action" href="{{ route('master-data.tags.create') }}">Add</a>
                            @endcan
                        </div>
                    </div>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>

    </div>
    @push('js')
        {!! $dataTable->scripts() !!}

        <script>
            const datatable = 'tag-table';

            handleAction(datatable)
            handleDelete(datatable)
            
        </script>
    @endpush
</x-master-layout>
