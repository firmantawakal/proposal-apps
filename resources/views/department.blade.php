@extends('layout.master')

@push('plugin-styles')
    <!-- Plugin css import here -->
@endpush

@section('content')
    <!-- Page content here -->
    <div class="row">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>{{ $message }}
                </div>
            @endif
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h6 class="card-title">Data Department</h6>
                    <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-success mb-3"><i
                            class="nav-icon fas fa-plus"></i></button>
                    <!-- Modal create-->
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Department</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('department.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label>Nama Department</label>
                                            <input type="text" name="department_name" class="form-control"
                                                placeholder="Nama Department ">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Nomor Awal Proposal</label>
                                            <input type="number" name="nomor" class="form-control"
                                                placeholder="100">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Struktur Nomor Proposal</label>
                                            <input type="text" name="struktur" class="form-control"
                                                placeholder="DEPARTMEN">
                                            <small>Nama struktur tanpa tanda "/"</small>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="dataTableExample" class="table table-stripe">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Department</th>
                                <th scope="col">Nomor Awal Proposal</th>
                                <th scope="col">Struktur Proposal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->department_name }}</td>
                                    <td>{{ $department->nomor }}</td>
                                    <td>{{ $department->struktur }}</td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#editModal{{ $department->id }}"
                                            class="btn btn-primary mr-2"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <!-- Modal edit-->
                                <div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('department.update', $department->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="form-group mb-3">
                                                        <label>Nama Department</label>
                                                        <input type="text" name="department_name" value="{{ $department->department_name }}"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Nomor Awal Proposal</label>
                                                        <input type="number" name="nomor" value="{{ $department->nomor }}" class="form-control"
                                                            placeholder="100">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Struktur Nomor Proposal</label>
                                                        <input type="text" name="struktur" value="{{ $department->struktur }}" class="form-control"
                                                            placeholder="DEPARTMEN">
                                                        <small>Nama struktur tanpa tanda "/"</small>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <!-- Plugin js import here -->
@endpush

@push('custom-scripts')
    <!-- Custom js here -->
@endpush
