<link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}">

<!-- Plugins css -->
<link href="{{ URL::asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/jquery-toast-plugin/jquery.toast.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@yield('plugin-css')

<!-- App css -->
<link href="{{ URL::asset('assets/css/bootstrap-creative.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{ URL::asset('assets/css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

<link href="https://fonts.googleapis.com/css2?family=Muli:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- icons -->
<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Custom CSS -->
<link href="{{ URL::asset('css/dashboard-common.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/modal-custom.css') }}" rel="stylesheet" type="text/css" />
@yield('custom-css')