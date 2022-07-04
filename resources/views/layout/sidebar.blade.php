<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      Proposal<span> Apps</span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li class="nav-item {{ active_class(['home']) }}">
        <a href="{{ url('home') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>

      @if (in_array(1,Session::get('level')))
      <li class="nav-item {{ active_class(['proposal']) }}">
        <a href="{{ url('proposal') }}" class="nav-link">
          <i class="link-icon" data-feather="arrow-right-circle"></i>
          <span class="link-title">Pengajuan Proposal</span>
        </a>
      </li>
      @endif

      @if (in_array(2,Session::get('level')))
      <li class="nav-item {{ active_class(['proposals/review/2']) }}">
        <a href="{{ url('proposals/review/2') }}" class="nav-link">
          <i class="link-icon" data-feather="inbox"></i>
          <span class="link-title">Review Proposal</span>&nbsp&nbsp
          @if (notification(2)!=0)
            <span class="badge bg-warning text-dark">{{notification(2)}}</span>
          @endif
        </a>
      </li>
      @endif

      @if (in_array(3,Session::get('level')))
      <li class="nav-item {{ active_class(['proposals/review/3']) }}">
        <a href="{{ url('proposals/review/3') }}" class="nav-link">
          <i class="link-icon" data-feather="inbox"></i>
          <span class="link-title">Approve 1 Proposal</span>&nbsp&nbsp
          @if (notification(3)!=0)
            <span class="badge bg-warning text-dark">{{notification(3)}}</span>
          @endif
        </a>
      </li>
      @endif

      @if (in_array(4,Session::get('level')))
        <li class="nav-item {{ active_class(['proposals/review/4']) }}">
            <a href="{{ url('proposals/review/4') }}" class="nav-link">
            <i class="link-icon" data-feather="inbox"></i>
            <span class="link-title">Approve 2 Proposal</span>&nbsp&nbsp
            @if (notification(4)!=0)
                <span class="badge bg-warning text-dark">{{notification(4)}}</span>
            @endif
            </a>
        </li>
      @endif

      <li class="nav-item nav-category" @if (Auth::user()->role=='user')style="display:none;" @endif>Master</li>
      <li class="nav-item {{ active_class(['department']) }}" @if (Auth::user()->role=='user')style="display:none;" @endif>
        <a href="{{ url('department') }}" class="nav-link">
          <i class="link-icon" data-feather="grid"></i>
          <span class="link-title">Department</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['position']) }}" @if (Auth::user()->role=='user')style="display:none;" @endif>
        <a href="{{ url('position') }}" class="nav-link">
          <i class="link-icon" data-feather="briefcase"></i>
          <span class="link-title">Jabatan</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['level']) }}" @if (Auth::user()->role=='user')style="display:none;" @endif>
        <a href="{{ url('level') }}" class="nav-link">
          <i class="link-icon" data-feather="bar-chart"></i>
          <span class="link-title">Level</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['category']) }}" @if (Auth::user()->role=='user')style="display:none;" @endif>
        <a href="{{ url('category') }}" class="nav-link">
          <i class="link-icon" data-feather="folder"></i>
          <span class="link-title">Kategori Proposal</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['user']) }}" @if (Auth::user()->role=='user')style="display:none;" @endif>
        <a href="{{ url('user') }}" class="nav-link">
          <i class="link-icon" data-feather="user"></i>
          <span class="link-title">User</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
