@extends('admin.layout')

@section('title', 'Modifier le récurrent - Admin')

@section('content')
<div class="finance-module text-slate-100 max-w-xl">
    <h1 class="text-2xl font-bold text-white mb-6">Modifier le récurrent</h1>

    <div class="stat-card">
        <form method="POST" action="{{ route('admin.finances.recurring.update', $recurring) }}">
            @method('PUT')
            @include('admin.finances.recurring._form')
        </form>
    </div>
</div>
@endsection
