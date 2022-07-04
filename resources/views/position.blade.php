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
                    <h6 class="card-title">Data Jabatan</h6>
                    <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-success mb-3"><i
                            class="nav-icon fas fa-plus"></i></button>
                    <!-- Modal create-->
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Jabatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('position.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label>Nama Department:</label>
                                            <select class="form-select" name="department_id">
                                                <option selected disabled>-- Pilih Department --</option>
                                                @foreach ($department as $dept)
                                                    <option value="{{$dept->id}}">{{$dept->department_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Jabatan:</label>
                                            <input type="text" name="position_name" class="form-control"
                                                placeholder="Nama Jabatan ">
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
                                <th scope="col">Nama Jabatan</th>
                                <th scope="col">Level</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $position->department->department_name }}</td>
                                    <td>{{ $position->position_name }}</td>
                                    <td>
                                        @php $dt_lvl = array() @endphp
                                        @foreach ($position->level as $poslev)
                                            @php $dt_lvl[] = $poslev->id @endphp
                                            <h5><span class="badge bg-light text-dark">{{$poslev->level_name}}</span></h5>
                                        @endforeach

                                    </td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#editModal{{ $position->id }}"
                                            class="btn btn-primary mr-2"><i class="fas fa-edit"></i></button>
                                        <button data-bs-toggle="modal" data-bs-target="#levelModal{{ $position->id }}"
                                            class="btn btn-info mr-2"><i class="fas fa-sitemap"></i></button>
                                    </td>
                                </tr>
                                <!-- Modal edit-->
                                <div class="modal fade" id="editModal{{ $position->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Jabatan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('position.update', $position->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="form-group mb-3">
                                                        <label>Nama Department:</label>
                                                        <select class="form-select" name="department_id">
                                                            <option selected disabled>-- Pilih Department --</option>
                                                            @foreach ($department as $dept)
                                                                <option value="{{$dept->id}}" @php echo ($dept->id == $position->department_id) ? 'selected' : '' ; @endphp>{{$dept->department_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Nama Jabatan:</label>
                                                        <input type="text" name="position_name" value="{{ $position->position_name }}"
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

                                 <!-- Modal level-->
                                 <div class="modal fade" id="levelModal{{ $position->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Level Jabatan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('position.editLevel') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label>Nama Jabatan:</label>
                                                        <input type="text" readonly name="position_name" value="{{ $position->position_name }}"
                                                            class="form-control" placeholder="">
                                                        <input type="hidden" name="position_id" value="{{ $position->id }}"
                                                            class="form-control" placeholder="">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="mb-2">Level:</label>
                                                        @foreach ($level as $lvl)
                                                            @php $check = (in_array($lvl->id, $dt_lvl)) ? 'checked' : '' ; @endphp
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" value="{{$lvl->id}}" name="level_id[]" class="form-check-input" id="checkInline" {{$check}}>
                                                                <label class="form-check-label">
                                                                    {{$lvl->level_name}}
                                                                </label>
                                                            </div>
                                                        @endforeach

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
                            @endforeach
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
