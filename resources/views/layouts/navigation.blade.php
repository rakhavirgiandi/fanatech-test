<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
          @if (Auth::user()->hasRole(['super-admin']))  
          <a class="nav-link" href="{{ route('inventory.index') }}">Inventory</a>
          @endif
          @if (Auth::user()->hasRole(['sales', 'super-admin', 'manager']))  
          <a class="nav-link" href="{{ route('sales.index') }}">Sales</a>
          @endif
          @if (Auth::user()->hasRole(['purchase', 'super-admin', 'manager']))  
          <a class="nav-link" href="{{ route('purchase.index') }}">Purchase</a>
          @endif
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Others
            </a>
            <ul class="dropdown-menu">
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                  </form>
                </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>