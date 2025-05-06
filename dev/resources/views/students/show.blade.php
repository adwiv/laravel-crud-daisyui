<x-layouts.crud>

<x-slot name="title">Student Details</x-slot>

<x-slot name="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{route('students.index')}}">Students</a></li>
    <li class="breadcrumb-item active">{{ $student->name ?? $student->id }}</li>
</x-slot>

<div class="card">
    <div class="card-header">
        <div class="card-title text-lg">Student Details</div>
        <div class="card-tools mr-0">
            <a class="btn btn-sm btn-outline-primary" href="{{route('students.edit', $student)}}">Edit Student</a>
        </div>
    </div>

    <div class="card-body p-0">
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
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title text-lg">Delete Student</div>
        <div class="card-tools mr-0">
        <form id="delete-form" action="{{route('students.destroy', $student)}}" method="POST" >
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete()">Delete Student</button>
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
</x-layouts.crud>
