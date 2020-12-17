<div class="topnav shadow-lg">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.index') }}" id="topnav-dashboard">
                            <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leads.index') }}" id="topnav-dashboard">
                            <i class="mdi mdi-pencil-box-outline mr-1"></i>Leads
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-application" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-graduation-cap mr-1"></i>Applications/Screening<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-application">
                            <a href="{{ route('application.index') }}" class="dropdown-item">Application</a>
                            <a href="https://tenantden.mysmartmove.com" target="_blank" class="dropdown-item">Screening with Transunion</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-lease" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-file mr-1"></i>Leases<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-lease">
                            <a href="http://rocketlawyer.go2cloud.org/aff_c?offer_id=190&aff_id=1675&url_id=5838" target="_blank" class="dropdown-item">Create a lease</a>
                            <a href="{{ route('lease.index') }}" class="dropdown-item">Find a Lease</a>
                            <a href="{{ route('lease.create') }}" class="dropdown-item">Add a Lease</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle chat-nav arrow-none" href="#" id="topnav-lease" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-comment mr-1"></i>Communication<span class="float-right text-right ml-1"><span class="badge badge-soft-danger pending" id="{{Auth::user()->id}}_current_pending">{{ getUnreadMessageCount() }}</span></span><div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-lease">
                            <a href="{{ route('tenant.index') }}" class="dropdown-item">Contacts</a>
                            <a href="{{ route('chat') }}" class="dropdown-item">Chat</a>
                            <a href="{{ route('compose.mail',['type'=>Auth::user()->full_name]) }}" class="dropdown-item">Mail</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('property.index') }}" id="topnav-dashboard">
                            <i class="mdi mdi-home-outline mr-1"></i>Rental Units
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rent.index') }}" id="topnav-dashboard">
                            <i class="mdi mdi-home-outline mr-1"></i>Pay Rent
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-id-badge fa-fw mr-1"></i>Account<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            <a href="{{route('vendor',['type'=>Auth::user()->full_name])}}" class="dropdown-item">Vendor</a>
                            {{--<a href="{{route('account.received',['type'=>Auth::user()->full_name])}}" class="dropdown-item">Received Amount</a>
                            <a href="{{route('account.debited')}}" class="dropdown-item">Debited Amount</a>--}}
                            <a href="{{route('report')}}" class="dropdown-item">Report</a> 
                            <a href="{{route('card.index')}}" class="dropdown-item">Add Card</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link chat-nav" href="{{route('invoices')}}" id="topnav-dashboard">
                            <i class="mdi mdi-home-outline mr-1"></i>Invoice<span class="float-right text-right ml-1"><span class="badge badge-soft-danger pending" id="{{Auth::user()->id}}_process">{{getInvoiceProcessCount()}}</span></span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog fa-fw mr-1"></i>Settings<div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            <a href="{{route('setting.index')}}" class="dropdown-item">Sms Settings</a>
                            <a href="{{route('setting.paypal')}}" class="dropdown-item">Paypal Settings</a>
                            <a href="{{route('latefees.index')}}" class="dropdown-item">Late Fees Settings</a>
                            <a href="{{route('card.index')}}" class="dropdown-item">Payment Method</a>
                            <a href="{{ route('document',['type'=>Auth::user()->full_name])}}" class="dropdown-item">Document Settings</a>
                        </div>
                    </li>
                    
                </ul>
            </div>
        </nav>
    </div>
</div>