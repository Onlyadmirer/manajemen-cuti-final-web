<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('divisions.index')" :active="request()->routeIs('divisions.*')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            {{ __('Manajemen Divisi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            {{ __('Manajemen User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.*')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            {{ __('Hari Libur') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->role === 'employee')
                        <x-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            {{ __('Cuti Saya') }}
                        </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, ['division_manager', 'hr']))
                        <x-nav-link :href="route('approvals.index')" :active="request()->routeIs('approvals.*')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                            {{ __('Persetujuan') }}
                            
                            {{-- Badge Notifikasi Jumlah Pending --}}
                            @php
                                $pendingCount = 0;
                                // Hitung pending untuk Manager (hanya divisi sendiri)
                                if(Auth::user()->role == 'division_manager' && Auth::user()->managedDivision) {
                                    $pendingCount = \App\Models\LeaveRequest::where('status', 'pending')
                                        ->whereHas('user', function($q){ 
                                            $q->where('division_id', Auth::user()->managedDivision->id)
                                              ->where('role', 'employee'); 
                                        })->count();
                                } 
                                // Hitung pending untuk HRD (Approved by Leader ATAU Manager mengajukan cuti)
                                elseif(Auth::user()->role == 'hr') {
                                    $pendingCount = \App\Models\LeaveRequest::where('status', 'approved_by_leader')
                                        ->orWhere(function($query) {
                                            $query->where('status', 'pending')
                                                  ->whereHas('user', function($q) {
                                                      $q->where('role', 'division_manager');
                                                  });
                                        })->count();
                                }
                            @endphp
                            
                            @if($pendingCount > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
                            @endif
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="text-right mr-2 hidden sm:block">
                                <div class="font-bold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400 uppercase tracking-wider">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('divisions.index')" :active="request()->routeIs('divisions.*')">
                    {{ __('Manajemen Divisi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.*')">
                    {{ __('Hari Libur') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'employee')
                <x-responsive-nav-link :href="route('leaves.index')" :active="request()->routeIs('leaves.*')">
                    {{ __('Cuti Saya') }}
                </x-responsive-nav-link>
            @endif

            @if(in_array(Auth::user()->role, ['division_manager', 'hr']))
                <x-responsive-nav-link :href="route('approvals.index')" :active="request()->routeIs('approvals.*')">
                    {{ __('Persetujuan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>