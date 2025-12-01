<ul role="list" class="flex flex-1 flex-col gap-y-7">
    <li>
        <ul role="list" class="-mx-2 space-y-1">
            <!-- DASHBOARD - Tous -->
            <li>
                <a href="{{ route('dashboard') }}"
                    class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
            </li>

            <!-- FOURNISSEURS - Admin & Responsable uniquement -->
            @if(auth()->user()->canManageFournisseurs())
                <li>
                    <a href="{{ route('fournisseurs.index') }}"
                        class="sidebar-item {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
                        </svg>
                        Fournisseurs
                    </a>
                </li>
            @endif

            <!-- PRODUITS - Tous (mais permissions différentes) -->
            <li>
                <a href="{{ route('produits.index') }}"
                    class="sidebar-item {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                    <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    Produits
                </a>
            </li>

            <!-- COMMANDES - Admin & Responsable uniquement -->
            @if(auth()->user()->canManageCommandes())
                <li>
                    <a href="{{ route('commandes.index') }}"
                        class="sidebar-item {{ request()->routeIs('commandes.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
                        </svg>
                        Commandes
                    </a>
                </li>
            @endif

            <!-- TRANSFERTS STOCK - Tous (mais vue différente selon rôle) -->
            <li>
                <a href="{{ route('transferts.index') }}"
                    class="sidebar-item {{ request()->routeIs('transferts.*') ? 'active' : '' }}">
                    <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    <span>Transferts Stock</span>
                </a>
            </li>

            <!-- MODULE VENTES - Tous -->
            <li
                x-data="{ open: {{ request()->routeIs('ventes.*') || request()->routeIs('caisses.*') || request()->routeIs('clients.*') ? 'true' : 'false' }} }">
                <button type="button"
                    class="sidebar-item w-full justify-between {{ request()->routeIs('ventes.*') || request()->routeIs('caisses.*') || request()->routeIs('clients.*') ? 'active' : '' }}"
                    @click="open = !open">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.001 3.001 0 013.75-.614A2.993 2.993 0 003.75 8.25c-.896 0-1.7.393-2.25 1.016A2.993 2.993 0 003.75 9.75c-.896 0-1.7.393-2.25 1.016A3.001 3.001 0 000 9.349" />
                        </svg>
                        <span>Ventes & Caisse</span>
                    </div>
                    <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-90': open }" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <ul x-show="open" x-transition class="mt-1 px-2 space-y-1">
                    <li>
                        <a href="{{ route('ventes.create') }}"
                            class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('ventes.create') ? 'bg-gray-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            Point de Vente
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ventes.index') }}"
                            class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('ventes.index') || request()->routeIs('ventes.show') ? 'bg-gray-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            Historique Ventes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('caisses.index') }}"
                            class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('caisses.*') ? 'bg-gray-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            Gestion Caisses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('clients.index') }}"
                            class="block rounded-md py-2 pl-9 pr-2 text-sm leading-6 {{ request()->routeIs('clients.*') ? 'bg-gray-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            Clients
                        </a>
                    </li>
                </ul>
            </li>

            <!-- RISTOURNES - Admin & Responsable uniquement -->
            @if(auth()->user()->canManageRistournes())
                <li>
                    <a href="{{ route('ristournes.index') }}"
                        class="sidebar-item {{ request()->routeIs('ristournes.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                        </svg>
                        <span>Ristournes</span>
                    </a>
                </li>
            @endif

            <!-- GESTION STOCKS - Tous -->
            <li>
                <a href="{{ route('rapports.stocks') }}"
                    class="sidebar-item {{ request()->routeIs('rapports.stocks') ? 'active' : '' }}">
                    <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span>Gestion Stocks</span>
                </a>
            </li>

            <!-- RAPPORTS - Tous (mais contenu filtré) -->
            <li>
                <a href="{{ route('rapports.index') }}"
                    class="sidebar-item {{ request()->routeIs('rapports.*') && !request()->routeIs('rapports.stocks') ? 'active' : '' }}">
                    <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <span>Rapports</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- ADMINISTRATION - Admin uniquement -->
    @if(auth()->user()->isAdmin())
        <li>
            <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wide">Administration</div>
            <ul role="list" class="-mx-2 mt-2 space-y-1">
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('analyses.index') }}"
                        class="sidebar-item {{ request()->routeIs('analyses.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                        </svg>
                        <span>Analyse Vendeurs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('parametres.index') }}"
                        class="sidebar-item {{ request()->routeIs('parametres.*') ? 'active' : '' }}">
                        <svg class="h-6 w-6 shrink-0 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
</ul>