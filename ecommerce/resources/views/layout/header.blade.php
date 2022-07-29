<header class="header-section">
	@livewire('header')
    <nav class="main-navbar">
        <div class="container">
            <!-- menu -->
            <ul class="main-menu">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="#">Women</a></li>
                <li><a href="#">Men</a></li>
                <li><a href="#">Jewelry
                    <span class="new">New</span>
                </a></li>
                <li><a href="{{ route('category') }}">Category</a></li>
                <li><a href="#">Pages</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('contact') }}">Contact Page</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>