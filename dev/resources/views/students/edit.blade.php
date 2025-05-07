<x-daisyui title="{{ $student ? 'Edit Student' : 'Create Student' }}">

    <x-slot name="breadcrumbs">
        <x-daisyui.breadcrumbs text="Home" href="{{ route('home') }}" />
        <x-daisyui.breadcrumbs href="{{ route('students.index') }}" text="Students" />
        @isset($student)
            <x-daisyui.breadcrumbs href="{{ route('students.show', $student) }}"
                text="{{ $student->name ?? $student->id }}" />
            <x-daisyui.breadcrumbs text="Edit" />
        @else
            <x-daisyui.breadcrumbs text="Create" />
        @endisset
    </x-slot>

    <div class="card card-border border-base-300 bg-base-100">
        <div class="card-body p-6 gap-5">
        <div class="flex justify-end">            
            <x-daisyui.button type="reset" form="crud-edit" class="btn-outline" color="warning" label="Reset" />
        </div>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <x-daisyui.alert type="warning" :title="$error" />
                @endforeach
            @endif

            <form id="crud-edit" method="post" class="needs-validation" novalidate autocomplete="off"
                action="{{ $action }}">
                @if ($student)
                    @method('PUT')
                @endif
                @csrf
                <x-daisyui.input type="hidden" name="_referrer" value="{{ old('_referrer', $referrer) }}" />
                <x-crud.model class="row" :model="$student" class="flex flex-col gap-3">
                    <x-daisyui.input  label="Name" id="name" name="name" required />
                    <x-daisyui.input  label="Email" id="email" name="email" type="email" required />
                    <x-daisyui.input  label="Phone" id="phone" name="phone" type="tel" required />
                    <x-daisyui.input  label="Date Of Birth" id="date_of_birth" name="date_of_birth" type="date"
                        required />
                    <x-daisyui.choices  label="Gender" id="gender" name="gender" type="radio" required
                        :options="['male' => 'Male', 'female' => 'Female']" />
                    <x-daisyui.choices  label="Degree" id="degree" name="degree" type="radio" required
                        :options="App\Enums\Degree::array()" />
                    <x-daisyui.choices  label="Likes" id="likes" name="likes" type="radio" required
                        :options="App\Enums\Likes::array()" />
                    <x-daisyui.input  label="Address" id="address" name="address" type="textarea" rows="5"
                        required />
                    <x-daisyui.choices  label="Is Active" id="is_active" name="is_active" type="select" required
                        :options="['FALSE' => 'FALSE', 'TRUE' => 'TRUE']" />
                </x-crud.model>
            </form>
        <div class="flex justify-between">
            <a class="btn btn-outline" href="{{ $referrer ?? route('students.index') }}">&laquo; Back</a>
            <x-daisyui.button type="submit" form="crud-edit" color="info" label="{{ $student ? 'Update Student' : 'Create Student' }}" />           
        </div>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('js/crud-edit.js') }}"></script>
        <script>
            trackFormModification("crud-edit");
        </script>
    @endpush

</x-daisyui>
