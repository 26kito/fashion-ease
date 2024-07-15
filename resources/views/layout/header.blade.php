<header class="header-section">
	@livewire('header')
    <nav class="main-navbar">
        <div class="container">
            <!-- menu -->
            <ul class="main-menu">
                <li>
                    <a href="{{ (request()->route()->getName() == 'home') ? '#' : route('home') }}" style="color: {{ (request()->route()->getName() == 'home') ? '#f51167' : ''}}">Home</a>
                </li>
                <li><a href="#">Women</a></li>
                <li><a href="#">Jewelry
                    <span class="new">New</span>
                </a></li>
                <li>
                    <a href="{{ (request()->route()->getName() == 'category' ? '#' : route('category') ) }}" style="color: {{ (request()->route()->getName() == 'category') ? '#f51167' : ''}}">Category</a>
                </li>
                <li><a href="#">Pages</a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('contact') }}">Contact Page</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>