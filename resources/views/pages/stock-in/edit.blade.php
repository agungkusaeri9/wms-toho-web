@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Stock In</h4>
                    <form action="{{ route('stock-ins.update', $item->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='received_date' class='mb-2'>Received Date</label>
                            <input type='date' name='received_date' id='received_date'
                                class='form-control @error('received_date') is-invalid @enderror'
                                value='{{ formatDate($item->received_date, 'Y-m-d') ?? old('received_date') }}'>
                            @error('received_date')
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
                            <a href="{{ route('stock-ins.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Stock In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
