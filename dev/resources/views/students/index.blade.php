<x-daisyui title="Student List">

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <x-daisyui.breadcrumbs text="Home" href="{{ route('home') }}" />
        <x-daisyui.breadcrumbs text="Students" />
    </x-slot>

    <div class="card card-border border-base-300 bg-base-100">
        <div class="card-body p-6 gap-6">
            <div class="flex justify-between">
                <div class="flex items-center gap-2">
                    <form action="{{ route('students.index') }}" method="GET" autocomplete="off">
                        <x-daisyui.input type="text" name="q" placeholder="Search students..."
                            value="{{ request('q') }}" required>
                            <x-slot:suffix>
                                <x-daisyui.button type="submit" icon="fas fa-search" class="btn-sm -me-2" />
                            </x-slot>
                        </x-daisyui.input>
                    </form>
                    @if (request('q'))
                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary btn-outline !text-md">
                            {{ request('q') }} <i class="fas fa-square-xmark text-lg"></i>
                        </a>
                    @endif
                </div>
                <a class="btn btn-primary" href="{{ route('students.create') }}"><i class="fas fa-plus"></i> Add</a>
            </div>
            <div class=" overflow-x-auto border border-base-300  rounded">
            <table class="table table-hover">
                <thead>
                    <tr class="bg-base-200/70">
                        <th class="">Name</th>
                        <th class="">Email</th>
                        <th class="">Phone</th>
                        <th class="">Date Of Birth</th>
                        <th class="">Gender</th>
                        <th class="">Degree</th>
                        <th class="">Likes</th>
                        <th class="">Is Active</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="">{{ $student->name }}</td>
                            <td class="">{{ $student->email }}</td>
                            <td class="">{{ $student->phone }}</td>
                            <td class="">{{ $student->date_of_birth->format('Y-m-d') }}</td>
                            <td class="">{{ $student->gender }}</td>
                            <td class="">{{ $student->degree }}</td>
                            <td class="">{{ $student->likes }}</td>
                            <td class="">{{ json_encode($student->is_active) }}</td>
                            <td>
                                <div class="flex gap-5">
                                    <div class="tooltip" data-tip="View">
                                        <a href="{{ route('students.show', $student) }}"
                                            class="p-2 flex items-center text-base-content/70 hover:text-base-content">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip" data-tip="Edit">
                                        <a href="{{ route('students.edit', $student) }}"
                                            class="p-2 flex items-center text-base-content/70 hover:text-base-content">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            {{-- // pagination --}}
            <div class="row justify-content-center">
                {{ $students->links() }}
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            table.table-hover tr:hover {
                background-color: #fafafa;
            }
        </style>
    @endpush
</x-daisyui>
