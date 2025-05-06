<x-layouts.crud>
<x-slot name="title">{{ $student ? 'Update Student' : 'Create Student' }}</x-slot>

<x-slot name="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{route('students.index')}}">Students</a></li>
    @isset($student)
        <li class="breadcrumb-item"><a href="{{route('students.show', $student)}}">{{ $student->name ?? $student->id }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    @else
        <li class="breadcrumb-item active">Create</li>
    @endisset
</x-slot>

<div class="card">
    <div class="card-header">
        <div class="card-title text-lg">{{ $student ? 'Edit Student' : 'Create Student' }}</div>
        <div class="card-tools mr-0">
            <button type="reset" form="crud-edit" class="btn btn-sm btn-outline-warning">Reset</button>
        </div>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-warning">
            <ul class="py-0 my-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="crud-edit" method="post" class="needs-validation" novalidate autocomplete="off" action="{{ $action }}">
            @if ($student) @method('PUT') @endif
            @csrf
            <input type="hidden" name="_referrer" value="{{ old('_referrer', $referrer) }}">
            <x-crud.model class="row" :model="$student">
            <x-crud.group id="name" label="Name" class="col-sm-6 col-lg-3">
                    <x-crud.input type="text" name="name" required/>
                </x-crud.group>

                <x-crud.group id="email" label="Email" class="col-sm-6 col-lg-3">
                    <x-crud.input type="email" name="email" required/>
                </x-crud.group>

                <x-crud.group id="phone" label="Phone" class="col-sm-6 col-lg-3">
                    <x-crud.input type="tel" name="phone" required/>
                </x-crud.group>

                <x-crud.group id="date_of_birth" label="Date Of Birth" class="col-sm-6 col-lg-3">
                    <x-crud.input type="date" name="date_of_birth" required/>
                </x-crud.group>

                @php
                    $genders = ['male'=>'Male','female'=>'Female'];
                @endphp
                <x-crud.group id="gender" label="Gender" class="col-sm-6 col-lg-3">
                    <x-crud.choices type="select" name="gender" required :options="$genders"/>
                </x-crud.group>

                <x-crud.group id="degree" label="Degree" class="col-sm-6 col-lg-3">
                    <x-crud.choices type="radio" name="degree" required :options="App\Enums\Degree::array()"/>
                </x-crud.group>

                <x-crud.group id="likes" label="Likes" class="col-sm-6 col-lg-3">
                    <x-crud.choices type="select" name="likes" required :options="App\Enums\Likes::array()"/>
                </x-crud.group>

                <x-crud.group id="address" label="Address" class="col-sm-6 col-lg-3">
                    <x-crud.textarea name="address" rows="5" required/>
                </x-crud.group>

                <x-crud.group id="is_active" label="Is Active" class="col-sm-6 col-lg-3">
                    <x-crud.choices type="select" name="is_active" :options="['FALSE','TRUE']" required/>
                </x-crud.group>
        </x-crud.model>
        </form>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-4">
                <a class="btn btn-link pl-0" href="{{ $referrer ?? route('students.index') }}">&laquo; Back</a>
            </div>
            <div class="col-sm-4 text-center">
            </div>
            <div class="col-sm-4 text-right">
                <button type="submit" form="crud-edit" class="btn btn-info">{{ $student ? 'Update Student' : 'Create Student' }}</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('js/crud-edit.js') }}"></script>
    <script>
        trackFormModification("crud-edit");
    </script>
@endpush

</x-layouts.crud>