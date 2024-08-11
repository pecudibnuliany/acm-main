<x-master-layout>
    @push('cssLibrary')
    <link href="{{ asset('') }}vendor/select2/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    @endpush
    @push('jsLibrary')
    <script src="{{ asset('') }}vendor/select2/js/select2.min.js"></script>
    @endpush
    <div class="main-content">
        <div class="title">
            Konfigurasi
        </div>
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h4>User</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @can('create konfigurasi/users')
                                <a class="mb-3 btn btn-primary action" href="{{ route('konfigurasi.users.create') }}">Add</a>
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
            const datatable = 'user-table';

            handleAction(datatable, function(res) {
                select2Init()
            })
            handleDelete(datatable)
            
        </script>
    @endpush
</x-master-layout>
