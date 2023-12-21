<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">

            {{-- <li class="menu">
                <a href="{{ route('employer.dashboard') }}"
                    {{ strpos(Route::currentRouteName(), 'backend.dashboard') === 0 ? 'data-active=true' : '' }}
                    class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li> --}}


            @if(Auth::guard('web')->user()->type == 'employer')

            <li class="menu">
                <a href="{{ route('employer.company.index')}}"
                    {{ strpos(Route::currentRouteName(), 'employer.company') === 0 ? 'data-active=true' : '' }}
                     aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Companies</span>
                    </div>
                </a>
            </li>
            @else
            
           
            <li class="menu">
                <a href="{{ route('employer.approver.company.allCompanies')}}"
                    {{ strpos(Route::currentRouteName(), 'employer.approver.company.allCompanies') === 0 ? 'data-active=true' : '' }}
                     aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Attendee Companies</span>
                    </div>
                </a>
            </li>

          
            @endif


            {{-- <li class="menu">
                <a href="{{ route('backend.leave.type.index')}}"
                    {{ strpos(Route::currentRouteName(), 'backend.leave.type') === 0 ? 'data-active=true' : '' }}
                     aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Leave Types</span>
                    </div>
                </a>

             
            </li> --}}

            


         
           

        </ul>
    </nav>
</div>
