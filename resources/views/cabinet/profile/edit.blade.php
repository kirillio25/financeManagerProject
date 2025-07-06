
@extends('layouts.cabinet_layout')

@section('title', 'Профиль')


@section('content')

<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Профиль</h3>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @include('cabinet.profile.partials.update-profile-information-form')
        </div>

        <div class="col-12 mt-4">
            @include('cabinet.profile.partials.update-password-form')
        </div>

        <div class="col-12 mt-4">
            @include('cabinet.profile.partials.delete-user-form')
        </div>
    </div>
</div>

@endsection



@push('scripts')

@endpush
