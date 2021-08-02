@extends('layouts.app')

@section('dataTables')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
@endsection

@section('content')
    <div class="flex justify-center">
{{--        {{ dd(get_defined_vars()['__data']) }}--}}
        <div class="w-8/12 bg-white p-6 rounded-lg">
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
                <tbody>

                @foreach ($items as $item)
                <tr>
                    <td><a href="{{ route('subscriber-edit',['id'=>$item->id]) }}">{{ $item->email }}</a></td>
                    <td>{{ $item->name }}</td>
                    @foreach ($item->fields as $field)
                        @if ($field->key == 'country')
                            <td>{{ $field->value }}</td>
                            @break
                        @endif
                    @endforeach
                    @if (isset($item->date_subscribe))
                        <td>{{ date('j/n/Y', strtotime($item->date_subscribe)) }}</td>
                    @else
                        <td>{{ date('j/n/Y', strtotime($item->date_created)) }}</td>
                    @endif
                    @if (isset($item->date_subscribe))
                        <td>{{ date('H:i:s', strtotime($item->date_subscribe)) }}</td>
                    @else
                        <td>{{ date('H:i:s', strtotime($item->date_created)) }}</td>
                    @endif
                    <td>Delete</td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>
        <div></div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready( function () {
            $('#subscriber-table').DataTable();
        });
    </script>
@endsection
