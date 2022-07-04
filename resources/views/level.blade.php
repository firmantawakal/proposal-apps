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
                    <h6 class="card-title">Data Level</h6>
                    <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-success mb-3"><i
                            class="nav-icon fas fa-plus"></i></button>
                    <!-- Modal create-->
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Level</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('level.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Level:</label>
                                            <input type="text" name="level_name" class="form-control"
                                                placeholder="Nama Level ">
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
                                <th scope="col">Nama Level</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($levels as $level)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $level->level_name }}</td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#editModal{{ $level->id }}"
                                            class="btn btn-primary mr-2"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <!-- Modal edit-->
                                <div class="modal fade" id="editModal{{ $level->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Level</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('level.update', $level->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="form-group mb-3">
                                                        <label>Nama Level:</label>
                                                        <input type="text" name="level_name" value="{{ $level->level_name }}"
                                                            class="form-control" placeholder="">
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
