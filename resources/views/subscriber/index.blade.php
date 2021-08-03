@extends('layouts.app')

@section('dataTables')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
@endsection

@section('content')
    <div class="flex justify-center">
{{--        {{ dd(get_defined_vars()['__data']) }}--}}
        <div class="bg-white p-6 rounded-lg">
            Subscribers
            <br />
            <br />
            <button onclick="location.href='{{ route('subscriber-create') }}'" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" type="button">
                Create Subscriber
            </button>
            <br />
            <br />
            <table id="subscriber-table" class="w-8/12 display">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Subscribe Date</th>
                    <th>Subscribe Time</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready( function () {
            var table = $('#subscriber-table').DataTable({
                "serverSide": true,
                "processing": true,
                "pagingType": "full_numbers",
                "language": {
                    "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                "scrollX": true,
                "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
                "ajax": {
                    "url": "{{ url('subscribers') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": {

                    }
                },
                "columns": [
                    { "data": "email" },
                    { "data": "name" },
                    { "data": "country" },
                    { "data": "date_subscribe_date" },
                    { "data": "date_subscribe_time" },
                    {
                        "data": "id",
                        "className": "dt-center",
                    }
                ],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [2,3,4,5]
                    },
                    {
                        targets: [0],
                        "searchable": true,
                        render: function ( data, type, row, meta ) {
                            if(type === 'display'){
                                data = '<a href="/subscribers/' + row.id + '/edit" style="color: blue;">' + data + '</a>';
                            }
                            return data;
                        }
                    },
                    {
                        targets: [5],
                        render: function ( data, type, row, meta ) {
                            if(type === 'display'){
                                data = '<button class="sub-delete bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id='+ data +'>DELETE</button>';
                            }
                            return data;
                        }
                    }
                ]
            });

            // Delete a record
            $('#subscriber-table tbody').on( 'click', 'button.sub-delete', function () {
                // console.log($(this).attr('data-id'));
                var subscruberId = $(this).attr('data-id');
                $.ajax({
                    method : 'DELETE',
                    url: '/subscribers/'+ subscruberId + '/delete',
                    dataType: 'json',
                    error:function(error) {
                        alert("An error has occurred");
                    }
                });

                table
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();
            } );

        });
    </script>
@endsection
