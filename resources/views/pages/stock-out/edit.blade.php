@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Stock Outs</h4>
                    <form action="{{ route('stock-outs.update', $item->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='date' class='mb-2'>Date</label>
                            <input type='date' name='date' id='date'
                                class='form-control @error('date') is-invalid @enderror'
                                value='{{ formatDate($item->date, 'Y-m-d') ?? old('received_date') }}'>
                            @error('date')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='notes' class='mb-2'>Notes</label>
                            <textarea name='notes' id='notes' cols='30' rows='3'
                                class='form-control @error('notes') is-invalid @enderror'>{{ $item->notes ?? old('notes') }}</textarea>
                            @error('notes')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('stock-outs.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Stock Outs</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
