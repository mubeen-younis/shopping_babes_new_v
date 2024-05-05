@extends('layouts.back-end.app')

@section('title', translate('Seller Finance'))

@push('css_or_js')
<style>

</style>
@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <i class="tio-dollar-outlined nav-icon"></i>
            </span>
            <span>Seller Finance </span>
        </h1>

        <!-- Nav -->
        <ul class="nav nav-tabs page-header-tabs">
            <li class="nav-item">
                <a class="nav-link @if ( $reportStatus == 'paid' ) active @endif"
                    href="{{ route('admin.finance.seller-report') }}">{{translate('Paid')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ( $reportStatus == 'not_paid' ) active @endif"
                    href="{{ route('admin.finance.seller-report', 'not-paid') }}">{{translate('Not Paid')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ( $reportStatus == 'cancelled' ) active @endif"
                    href="{{ route('admin.finance.seller-report', 'cancelled') }}">{{translate('Cancelled')}}</a>
            </li>
        </ul>

    </div>
    <!-- End Page Header -->

    <div class="card">
        <div class="card-body">
            <!-- Table -->
            <div class="table-responsive datatable-custom fz--14px">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-capitalize">ID</th>
                            <th class="text-capitalize w-20p">Seller Name</th>
                            <th class="text-capitalize">Net Amount</th>
                            <th class="text-capitalize">From</th>
                            <th class="text-capitalize">To</th>
                            <th class="text-capitalize w-110px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @foreach($finance as $shop)
                        @if( $shop['seller'] )
                        <tr>
                            <th class="justify-content-center">{{$shop['id']}}</th>
                            <th class="justify-content-center">{{$shop['seller']['f_name']}}
                                {{$shop['seller']['l_name']}}</th>
                            <th class="justify-content-center">{{
                                \App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($shop['net_amount']))
                                }}</th>
                            <th class="justify-content-center">{{$shop['date_from']}}</th>
                            <th class="justify-content-center">{{$shop['date_to']}}</th>
                            <th class="text-capitalize w-110px">
                                <div class="btn--container justify-content-center">
                                    @if ( $reportStatus == 'paid' )
                                    <!-- <a class="btn btn-xs btn-danger" href="{{ route('admin.finance.seller-update-report', ['report_status' => 'not-paid', 'report_id' => $shop['id']]) }}" title="{{translate('Mark as Not Paid')}}">Mark Not Paid</a> -->
                                    @endif

                                    @if ( $reportStatus == 'not_paid' )
                                    <a class="btn btn-xs btn-success"
                                        href="{{ route('admin.finance.seller-update-report', ['report_status' => 'paid', 'report_id' => $shop['id']]) }}"
                                        title="{{translate('Mark as Paid')}}">Mark as Paid</a>
                                    <a class="btn btn-xs btn-danger" href="javascript:"
                                        onclick="form_alert('mark-cancel-{{$shop['id']}}','Want to mark this cancel?')"
                                        title="Mark Cancelled">Mark Cancelled</a>
                                    <form
                                        action="{{ route('admin.finance.seller-update-report', ['report_status' => 'cancelled', 'report_id' => $shop['id']]) }}"
                                        method="get" id="mark-cancel-{{$shop['id']}}">
                                        @csrf
                                        <input type="hidden" name="_method" value="Mark Cancel">
                                    </form>
                                    @endif

                                    @if ( $reportStatus == 'cancelled' )
                                    <!-- <a class="btn btn-xs btn-success" href="{{ route('admin.finance.seller-update-report', ['report_status' => 'not-paid', 'report_id' => $shop['id']]) }}" title="{{translate('Mark as Not Paid')}}">Mark Not Paid</a> -->
                                    @endif

                                    <a class="btn btn-xs btn-primary" target="_blank"
                                        href="{{ route('admin.finance.seller-print-report', $shop['id']) }}"
                                        title="{{translate('messages.view')}}">View</a>
                                </div>
                            </th>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Stats -->
</div>
@endsection

@push('script_2')
<script>
    $(document).on('ready', function () {
        });
</script>
@endpush
