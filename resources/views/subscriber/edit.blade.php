@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            Edit Subscriber
            <br /><br />
{{--            {{ dd(get_defined_vars()['__data']) }}--}}
            @if(isset($item->error))
                <p>An error occurred: {{ $item->error->message }}</p>
            @else
                <form action="{{ route('subscriber-edit',['id'=>$item->id]) }}" method="post">
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="sr-only">Name</label>
                        <input type="text" name="name" id="name" placeholder="Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror" value="{{ $item->name }}">

                        @error('name')
                        <div class="text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="country" class="sr-only">Country</label>
                        <select name="country" id="country" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{$currentCountry == $country ? "selected" : ""  }}>{{ $country }}</option>
                            @endforeach
                        </select>

                        @error('country')
                        <div class="text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
