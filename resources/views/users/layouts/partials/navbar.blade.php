<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
    <a href="" class="navbar-brand p-0">
        <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Restoran</h1>
        <!-- <img src="img/logo.png" alt="Logo"> -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0 pe-4">  
            @if(Auth::user()->type == "admin") <!-- Assuming 1 is for Admin -->
                <a href="{{ route('admin.home') }}" class="nav-item nav-link">Admin Dashboard</a>
                <a href="" class="nav-item nav-link">Manage Orders</a>
                <a href="" class="nav-item nav-link">Manage Users</a>
                <!-- Add more admin-specific links here -->
            @else
                <a href="/" class="nav-item nav-link active">Home</a>
                <a href="{{route('cart.show')}}" class="nav-item nav-link">My Cart</a>
                <a href="{{route('orders.index')}}" class="nav-item nav-link">My Orders</a>
            @endif
        </div>
        @if(Auth::check())
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary py-2 px-4">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary py-2 px-4">Sign in</a>
        @endif
    </div>
</nav>
