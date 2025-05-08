<x-daisyui title="Student Details">

<x-slot name="breadcrumbs">
    <x-daisyui.breadcrumbs text="Home" href="{{route('home')}}"/>
    <x-daisyui.breadcrumbs text="Students" href="{{route('students.index')}}" />
    <x-daisyui.breadcrumbs text="{{ $student->name ?? $student->id }}" />    
</x-slot>

<div class="card card-border border-base-300 bg-base-100">
    <div class="card-body p-6 gap-6">
        <div class="flex justify-end">
            <a class="btn btn-primary btn-outline" href="{{route('students.edit', $student)}}">Edit Student</a>
        </div>    
        <div class="overflow-x-auto border border-base-300  rounded">
            <table class="table table-sm table-hover">
                <tr>
                    <td>Name</td>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $student->email }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $student->phone }}</td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td>{{ $student->date_of_birth->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>{{ $student->gender }}</td>
                </tr>
                <tr>
                    <td>Degree</td>
                    <td>{{ $student->degree }}</td>
                </tr>
                <tr>
                    <td>Likes</td>
                    <td>{{ $student->likes }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $student->address }}</td>
                </tr>
                <tr>
                    <td>Is Active</td>
                    <td>{{ json_encode($student->is_active) }}</td>
                </tr>
            </table>
        </div>
        <div class="flex justify-between items-center">   
            <h3 class="font-semibold">Delete Student</h3>        
            <form id="delete-form" action="{{route('students.destroy', $student)}}" method="POST" >
                @csrf
                @method('DELETE')
                <x-daisyui.button type="button" class="btn-outline"  color="error" onclick="confirmDelete()" label="Delete Student" />
            </form>        
            @section('plugins.Sweetalert2', true)
            <script>
                function confirmDelete() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        console.log(result);
                        if (result.isConfirmed) {
                            document.getElementById('delete-form').submit();
                        }
                    })
                }
            </script>
        </div>
    </div>
</div>
</x-daisyui>