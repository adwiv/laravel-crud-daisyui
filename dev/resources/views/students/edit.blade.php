<x-daisyui title="{{ $student ? 'Update Student' : 'Create Student' }}">

  <x-slot name="breadcrumbs">
    <x-daisyui.breadcrumbs text="Home" href="{{ route('home') }}" />
    <x-daisyui.breadcrumbs href="{{ route('students.index') }}" text="Students" />

    @isset($student)
      <x-daisyui.breadcrumbs href="{{ route('students.show', $student) }}" text="{{ $student->name ?? $student->id }}" />
      <x-daisyui.breadcrumbs text="Edit" />
    @else
      <x-daisyui.breadcrumbs text="Create" />
    @endisset
  </x-slot>
  <div class="card card-border border-base-300 bg-base-100">
    <div class="card-body p-6 gap-5">
      <form id="crud-edit" method="post" class="needs-validation" novalidate autocomplete="off" action="{{ $action }}">
        @if ($student)
          @method('PUT')
        @endif
        @csrf
        <x-daisyui.input type="hidden" name="_referrer" value="{{ old('_referrer', $referrer) }}" />
        <x-crud.model class="flex flex-col gap-3" :model="$student">
          <x-daisyui.input type="text" id="name" label="Name" name="name" required error="Name is required" />
          <x-daisyui.input type="email" id="email" label="Email" name="email" required error="Email is required" />
          <x-daisyui.input type="tel" id="phone" label="Phone" name="phone" required error="Phone is required" />
          <x-daisyui.input type="date" id="date_of_birth" label="Date Of Birth" name="date_of_birth" required
            error="Date of birth is required" />
          @php
            $genders = ['male' => 'Male', 'female' => 'Female'];
          @endphp
          <x-daisyui.choices type="radio" id="gender" label="Gender" name="gender" :options="$genders" error="Gender is required"
            required />
          <x-daisyui.choices type="radio" id="degree" label="Degree" name="degree" required :options="App\Enums\Degree::array()"
            error="Degree is required" />
          <x-daisyui.choices type="radio" id="likes" label="Likes" name="likes" required :options="App\Enums\Likes::array()"
            error="Likes are required" />
          <x-daisyui.textarea id="address" label="Address" name="address" rows="5" required />
          <x-daisyui.choices type="select" id="is_active" label="Is Active" name="is_active" :options="['FALSE', 'TRUE']" required />
        </x-crud.model>
      </form>
      <div class="flex justify-between">
        <div>
          <a class="btn" href="{{ $referrer ?? route('students.index') }}">Back</a>
          <x-daisyui.button type="reset" form="crud-edit" class="btn-outline" color="warning" label="Reset" />
        </div>
        <div>
          <x-daisyui.button type="submit" form="crud-edit" color="info" label="{{ $student ? 'Update Student' : 'Create Student' }}" />
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

</x-daisyui>
