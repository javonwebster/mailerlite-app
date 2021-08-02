@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            Create Subscriber
            <br /><br />
            @if(isset($item->error))
                <p>An error occurred: {{ $item->error->message }}</p>
            @else
                <form action="{{ route('subscriber-create') }}" method="post">
                    @method('POST')
                    <div class="mb-4">
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email Address" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('email') border-red-500 @enderror" value="{{ old('email') }}">

                        @error('email')
                        <div class="text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="sr-only">Name</label>
                        <input type="text" name="name" id="name" placeholder="Name" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror" value="{{ old('name') }}">

                        @error('name')
                        <div class="text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="country" class="sr-only">Country</label>
                        <select name="country" id="country" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                            @foreach($countries as $key => $country)
                                <option value="{{ $key == 0 ? "" : $country }}" {{ old('name') == $country ? "selected" : ""  }}>{{ $country }}</option>
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
