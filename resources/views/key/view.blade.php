@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            @if (!is_null($currentKey))
                <div class="text-green-600 mt-2 text-sm">
                    Saved API Key: {{ $currentKey->api_key }}
                </div>
            @endif
            Enter API Key *
            @if (request()->get('no-key') and is_null($currentKey))
            <div class="text-red-500 mt-2 text-sm">
                You cannot manage subscribers because you have not saved a valid API Key.
            </div>
            @endif
            <form action="{{ route('manage-api-key') }}" method="post">
                <div class="mb-4">
                    <label for="api-key" class="sr-only">API Key</label>
                    <input type="text" name="api-key" id="api-key" placeholder="Your API Key" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('api-key') border-red-500 @enderror" value="{{ old('api-key') }}">

                    @error('api-key')
                    <div class="text-red-500 mt-2 text-sm">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
