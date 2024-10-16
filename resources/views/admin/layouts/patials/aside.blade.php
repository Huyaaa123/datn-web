<div class="toggle">
    <div class="logo">
        <img src="../../../admindb/images/lg.jpg">
        <h2>Rolex<span class="danger">Watch</span></h2>
    </div>
    <div class="close" id="close-btn">
        <span class="material-icons-sharp">
            close
        </span>
    </div>
</div>

<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}">
        <span class="material-icons-sharp">
            dashboard
        </span>
        <h3>Dashboard</h3>
    </a>
    <a href="{{ route('admin.users') }}">
        <span class="material-icons-sharp">
            person_outline
        </span>
        <h3>Users</h3>
    </a>
    <a href="{{ route('admin.category.index') }}">
        <span class="material-icons-sharp">
            category
        </span>
        <h3>Category</h3>
    </a>
    <a href="{{ route('admin.product.index') }}" >
        <span class="material-icons-sharp">
            inventory_2
        </span>
        <h3>Products</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            mail_outline
        </span>
        <h3>Tickets</h3>
        {{-- <span class="message-count">27</span> --}}
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            inventory
        </span>
        <h3>Sale List</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            report_gmailerrorred
        </span>
        <h3>Reports</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            settings
        </span>
        <h3>Settings</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            add
        </span>
        <h3>New Login</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">
            logout
        </span>
        <h3>Logout</h3>
    </a>
</div>
