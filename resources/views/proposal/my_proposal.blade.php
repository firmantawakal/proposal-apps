@extends('layout.master')

@push('plugin-styles')
    <style>
        .timeline {
            border-left: 3px solid #6571ff;
            background: transparent !important;
            /* margin: 0 auto; */
            margin-left: 20px !important;
            max-width: 100% !important;
        }
    </style>
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
            @if($errors->any())
                <div class="alert alert-danger">
                    <label>Whoops!</label> There were some problems with your input.<br><br>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h6 class="card-title">Data Pengajuan Proposal</h6>
                    <button data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-success mb-3"><i
                            class="nav-icon fas fa-plus"></i></button>
                    <!-- Modal create-->
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Proposal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('proposal.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label>Nomor</label>
                                            <input type="text" name="nomor_surat" class="form-control"
                                                value="{{$nomor_surat}}" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Judul Proposal</label>
                                            <input type="text" name="title" class="form-control"
                                                placeholder="Nama Proposal">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Kategori Proposal</label>
                                            <select class="form-control" name="category_id">
                                                @foreach ($category as $cat)
                                                    <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Biaya Diajukan</label>
                                            <input type="number" name="cost" class="form-control"
                                                placeholder="Biaya yang diajukan">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="formFile">Berkas Proposal</label>
                                            <input class="form-control" name="document" type="file" id="formFile" accept="image/*,.pdf">
                                            <small>Format file yang diizinkan : .jpg, .jpeg, .png, .pdf</small>
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
                                <th style="max-width:30px">No.</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nomor</th>
                                <th scope="col">Judul Proposal / Kategori</th>
                                <th scope="col">Biaya yg Diajukan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($proposals as $proposal)
                                @php
                                    if($proposal->status===0 && ($proposal->review_status===0 || $proposal->approve1_status===0 || $proposal->approve2_status===0)){
                                        $status = '<span class="badge rounded-pill bg-secondary">Ditolak</span>';
                                    }elseif($proposal->status==1){
                                        $status = '<span class="badge rounded-pill bg-success">Diterima</span>';
                                    }else{
                                        $status = '<span class="badge rounded-pill bg-warning text-dark">Diproses</span>';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ dateIndo($proposal->created_at) }}</td>
                                    <td>{{$proposal->nomor_surat}}</td>
                                    <td>{!! $proposal->title.'<br>('.$proposal->category_name.')' !!}</td>
                                    <td>{{ rupiah($proposal->cost) }}</td>
                                    <td>{!! $status !!}</td>
                                    <td>
                                        <button data-bs-toggle="modal" data-bs-target="#timelineModal{{ $proposal->id }}"
                                            class="btn btn-primary mr-2"><i class="fas fa-book-reader"></i></button>
                                        <a class="btn btn-info" href="/document/{{ $proposal->document}}" target="_blank">
                                            <i class="fas fa-file"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal timeline-->
                                <div class="modal fade" id="timelineModal{{ $proposal->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Timeline Proposal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="timeline">
                                                    @php
                                                        if($proposal->approve2_status!==NULL){
                                                            $date2 = dateIndo($proposal->approve2_date);
                                                            $approve2_status='ditolak';
                                                            if ($proposal->approve2_status==1 && $proposal->status==1) {
                                                                $approve2_status='diterima';
                                                            }
                                                            echo '<li class="event">
                                                                    <h3 class="title">'.$date2.' - Proposal '.$approve2_status.'</h3>
                                                                    <ul>
                                                                       <li> Pemeriksa : '.$proposal->app2_name.'</li>
                                                                       <li> Komentar : '.$proposal->approve2_comment.'</li>
                                                                    </ul>
                                                                </li>';
                                                        }

                                                        if($proposal->approve1_status!==NULL){
                                                            $date2 = dateIndo($proposal->approve1_date);
                                                            $approve1_status='ditolak';
                                                            if ($proposal->approve1_status==1 && $proposal->approve2_status!=NULL) {
                                                                $approve1_status='telah di approve tahap 1';
                                                            }elseif ($proposal->approve1_status==1 && $proposal->status==1) {
                                                                $approve1_status='diterima';
                                                            }
                                                            echo '<li class="event">
                                                                    <h3 class="title">'.$date2.' - Proposal '.$approve1_status.'</h3>
                                                                    <ul>
                                                                       <li> Pemeriksa : '.$proposal->app1_name.'</li>
                                                                       <li> Komentar : '.$proposal->approve1_comment.'</li>
                                                                    </ul>
                                                                </li>';
                                                        }

                                                        if($proposal->review_status!==NULL){
                                                            $date = dateIndo($proposal->review_date);
                                                            $review_status='ditolak';
                                                            if ($proposal->review_status==1) {
                                                                $review_status='telah di review';
                                                            }
                                                            echo '<li class="event">
                                                                    <h3 class="title">'.$date.' - Proposal '.$review_status.'</h3>
                                                                    <ul>
                                                                       <li> Pemeriksa : '.$proposal->rev_name.'</li>
                                                                       <li> Komentar : '.$proposal->review_comment.'</li>
                                                                    </ul>
                                                                </li>';
                                                        }
                                                    @endphp
                                                        <li class="event">
                                                            <h3 class="title"> {{dateIndo($proposal->created_at)}} - Proposal dibuat</h3>
                                                            <ul>
                                                                <li> Judul : {{$proposal->title}} </li>
                                                                <li> Cost  : {{rupiah($proposal->cost)}} </li>
                                                            </ul>
                                                        </li>

                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
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
