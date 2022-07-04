@extends('layout.master')

@push('plugin-styles')
    <style>
        .timeline {
            background: transparent !important;
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
                    <h6 class="card-title">Data Proposal</h6>

                    <table id="dataTableExample" class="table table-stripe">
                        <thead>
                            <tr>
                                <th style="max-width:30px">No.</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nomor</th>
                                <th scope="col">User</th>
                                <th scope="col">Jabatan / Departmen</th>
                                <th scope="col">Judul & Kategori</th>
                                <th scope="col">Jumlah Dana</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php dd($proposals) @endphp --}}
                            @forelse ($proposals as $proposal)
                                @php
                                    $button = null;
                                    $style = null;
                                @endphp

                                @if ($proposal->status==0 &&
                                        (
                                            ($proposal->isReviewed==false && Request::segment(3)==2) ||
                                            ($proposal->isApprove1==false && Request::segment(3)==3 && $proposal->isReviewed==1) ||
                                            ($proposal->isApprove2==false && Request::segment(3)==4 && $proposal->isApprove1==1)
                                        )
                                    )
                                    @php
                                        $button = '<button data-bs-toggle="modal" data-bs-target="#editModal'.$proposal->id.'"
                                                class="btn btn-success mr-2"><i class="fas fa-file-signature"></i></button>';

                                        $style = 'table-warning';
                                    @endphp
                                @endif
                                <tr class="{{$style}}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ dateIndo($proposal->created_at) }}</td>
                                    <td>{{$proposal->nomor_surat}}</td>
                                    <td>{{ $proposal->username }}</td>
                                    <td>{!! $proposal->position_name.' /<br>'.$proposal->department_name !!}</td>
                                    <td>{!! $proposal->title.'<br>('.$proposal->category_name.')' !!}</td>
                                    <td>{{ rupiah($proposal->cost) }}</td>
                                    <td>
                                        {!!$button!!}
                                        <button data-bs-toggle="modal" data-bs-target="#timelineModal{{ $proposal->id }}"
                                            class="btn btn-primary mr-2"><i class="fas fa-book-reader"></i></button>
                                        <a class="btn btn-info" href="/document/{{ $proposal->document}}" target="_blank">
                                                <i class="fas fa-file"></i></a>
                                    </td>
                                </tr>
                                <!-- Modal tindaklanjut -->
                                <div class="modal fade" id="editModal{{ $proposal->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tindaklanjut Proposal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('proposal.review_action', $proposal->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="form-group mb-3">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="status" id="" value="0" checked>
                                                            Tolak
                                                        </label>&nbsp;&nbsp;&nbsp;
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="status" id="" value="1">
                                                            {{$proposal->textFinish}}
                                                        </label>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="">Keterangan</label>
                                                        <textarea class="form-control" name="comment" id="" rows="3"></textarea>
                                                        <input type="hidden" name="category_id" value="{{$proposal->category_id}}">
                                                        <input type="hidden" name="cost" value="{{$proposal->cost}}">
                                                        <input type="hidden" name="accessLevel" value="{{$accessLevel}}">
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
