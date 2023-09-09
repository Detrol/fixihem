@extends('layout.admin')

@section('content')



            <!-- PAGE TITLE -->
            <header class="mt-lg-n6">
                <h1 class="h4">Good morning, John!</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item text-muted active" aria-current="page">You've got 2 new sales today</li>
                    </ol>
                </nav>
            </header>


            <!-- Primary -->
            <div class="section rounded mb-3">

                <div class="d-flex align-items-center justify-content-between border-bottom pb-4 my-2">

                    <div class="w-100">
                        <h2 class="h5 mb-0">Monthly orders</h2>
                        <small class="small text-muted">Monthly revenue of 2022</small>
                    </div>

                    <!-- save image -->
                    <a href="#" data-chartjs-id="visitsvsorders" data-file-name="visitsvsorders" class="chartjs-save btn btn-sm btn-light text-primary-hover rounded-circle float-end m-0" data-bs-toggle="tooltip" title="Save Chart" aria-label="Save Chart">
                        <i class="fi fi-arrow-download m-0"></i>
                    </a>

                </div>

                <div class="row g-4">

                    <div class="col-12 col-lg-7 col-xl-9 order-1 order-xl-2">

                        <div class="position-relative h-100" style="min-height:250px; max-height:500px">
                            <canvas id="visitsvsorders" class="chartjs"
                                    data-chartjs-dots="false"
                                    data-chartjs-legend="false"
                                    data-chartjs-grid="true"
                                    data-chartjs-tooltip="true"

                                    data-chartjs-title=""
                                    data-chartjs-xaxes-label=""
                                    data-chartjs-yaxes-label=""
                                    data-chartjs-line-width="5"

                                    data-chartjs-type="line"
                                    data-chartjs-labels='["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]'
                                    data-chartjs-datasets='[{
                      "label": "Orders",
                      "data": [14, 16, 16, 14, 13, 12, 14, 14, 13, 14, 12, 10],
                      "fill": true,
                      "backgroundColor": "rgba(163, 167, 255, 0.4)"
                    }]'></canvas>

                        </div>

                    </div>

                    <div class="col-12 col-lg-5 col-xl-3 order-2 order-xl-1">

                        <!-- reveal on hover container -->
                        <div class="position-relative animate-reveal-up-item">

                            <!-- card rating -->
                            <div class="position-relative reveal-item bg-white shadow-lg-hover rounded-xl p-5 p-md-3 py-xl-5 p-4-xs z-index-2">

                                <h6 class="text-muted text-center">MONTHLY REVENUE</h6>

                                <div class="text-center mt-5">

                                    <!-- chart -->
                                    <div class="h--120 easypie d-inline-block position-relative z-index-1"
                                         data-bar-color="#574fec"
                                         data-track-color="#eaeaea"
                                         data-scale-color="#cccccc"
                                         data-scale-length="0"
                                         data-line-width="10"
                                         data-line-cap="round"

                                         data-size="120"
                                         data-percent="61">

                                        <div class="absolute-full d-middle pt-0 pb-2">
                                            <div class="flex-none text-center">
                                                <span class="d-block">61%</span>
                                                <small class="d-block text-muted">Conversion</small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /chart -->


                                    <p class="text-muted mt-4 mb-2">Sales this month</p>
                                    <p class="lead fw-medium mb-0 text-primary">
                                        $<span data-toggle="count" data-count-to="2144.55" data-count-duration="2500">0</span>
                                    </p>

                                    <!-- 2 cols -->
                                    <div class="d-flex mt-4 fs--15">
                                        <div class="flex-fill"><!-- col 1 -->
                                            <p class="text-gray-500 m-0">last month</p>
                                            <p class="text-gray-600 m-0 fw-medium">$12978.51</p>
                                        </div>

                                        <div class="flex-fill"><!-- col 2 -->
                                            <p class="text-gray-500 m-0">last 3 months</p>
                                            <p class="text-gray-600 m-0 fw-medium">$4122.11</p>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- /card rating -->

                            <!-- link under -->
                            <div class="fs--16 position-absolute left-0 right-0 bottom-0 z-index-1 pt-3 px-2">

                                <div class="clearfix bg-light p-1 row-pill">
                                    <a href="#!" class="btn btn-pill btn-sm btn-success py-1 mb-0 float-start transition-hover-end" title="Detailed Revenue" aria-label="Detailed Revenue">View</a>
                                    <span class="d-block pt-1 pl-2 pr-2 text-muted text-truncate">
                                        more
                                    </span>
                                </div>

                            </div>

                        </div>
                        <!-- /reveal on hover container -->

                    </div>

                </div>

            </div>
            <!-- /Primary -->


            <!-- widgets -->
            <div class="row g-3 mb-3">

                <div class="col-12 col-md-6 col-xl-3">

                    <!-- small graph widget -->
                    <div class="bg-white shadow-md text-dark p-5 rounded text-center">

                        <!-- icon -->
                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-gray-900 rounded-circle" style="width: 100px;height:100px">
                            <svg width="54px" height="54px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                    <path fill="currentColor" opacity="0.3" fill-rule="nonzero" d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"></path>
                                    <path fill="currentColor" fill-rule="nonzero" d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"></path>
                                </g>
                            </svg>
                        </span>

                        <h3 class="fs-5 mt-4">New Customers</h3>
                        <p>Last 30 days</p>

                        <!-- chart -->
                        <div class="position-relative" style="height:100px">
                            <canvas class="chartjs"
                                    data-chartjs-dots="false"
                                    data-chartjs-legend="false"
                                    data-chartjs-grid="false"
                                    data-chartjs-tooltip="true"

                                    data-chartjs-line-width="3"
                                    data-chartjs-type="line"

                                    data-chartjs-labels='["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]'
                                    data-chartjs-datasets='[{
                          "label": "Customers",
                          "data": [11, 11, 17, 11, 15, 12, 13, 12, 11, 12, 7, 8],
                          "fill": false,
                          "backgroundColor": "rgba(255, 99, 132, 1)"
                      }]'></canvas>
                        </div>

                    </div>
                    <!-- /small graph widget -->

                </div>

                <div class="col-12 col-xl-6">

                    <div class="portlet">

                        <div class="portlet-header">

                            <!-- dropdown options -->
                            <div class="float-end dropdown">
                                <button type="button" class="dropdown-toggle btn btn-sm btn-light px-2 py-1 mt-n1" id="dropdownGraph1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="options">
                                    <span>
                                        <svg width="18px" height="18px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                                        </svg>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-clean" aria-labelledby="dropdownGraph1">
                                    <div class="dropdown-header smaller px-4 text-muted fw-medium">Export options</div>
                                    <a href="#" class="dropdown-item">Export CSV</a>
                                    <a href="#" class="dropdown-item">Export XLS</a>
                                    <a href="#" class="dropdown-item">Export PDF</a>
                                    <a href="#" class="dropdown-item">Print</a>
                                </div>
                            </div>
                            <!-- /dropdown options -->

                            <div class="text-muted text-truncate fw-medium">
                                Monthly Conversions
                            </div>

                        </div>

                        <div class="position-relative h-100" style="min-height:250px">
                            <canvas class="chartjs"
                                    data-chartjs-dots="false"
                                    data-chartjs-legend="false"
                                    data-chartjs-grid="true"
                                    data-chartjs-tooltip="true"

                                    data-chartjs-line-width="3"
                                    data-chartjs-type="line"

                                    data-chartjs-labels='["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]'
                                    data-chartjs-datasets='[{
                      "label": "Orders",
                      "data": [11, 11, 17, 11, 15, 12, 13, 16, 11, 18, 20, 21],
                      "fill": true,
                      "backgroundColor": "rgba(201, 203, 207, 0.3)",
                      "borderColor": "rgba(255, 99, 132, 1)",
                      "borderWidth": 1
                    }]'></canvas>
                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-6 col-xl-3">

                    <!-- small graph widget -->
                    <div class="bg-white shadow-md text-dark p-5 rounded text-center">

                        <!-- icon -->
                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-gray-900 rounded-circle" style="width: 100px;height:100px">
                            <svg width="54px" height="54px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path fill="currentColor" opacity="0.3" fill-rule="nonzero" d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"></path>
                                    <path fill="currentColor" d="M3.28077641,9 L20.7192236,9 C21.2715083,9 21.7192236,9.44771525 21.7192236,10 C21.7192236,10.0817618 21.7091962,10.163215 21.6893661,10.2425356 L19.5680983,18.7276069 C19.234223,20.0631079 18.0342737,21 16.6576708,21 L7.34232922,21 C5.96572629,21 4.76577697,20.0631079 4.43190172,18.7276069 L2.31063391,10.2425356 C2.17668518,9.70674072 2.50244587,9.16380623 3.03824078,9.0298575 C3.11756139,9.01002735 3.1990146,9 3.28077641,9 Z M12,12 C11.4477153,12 11,12.4477153 11,13 L11,17 C11,17.5522847 11.4477153,18 12,18 C12.5522847,18 13,17.5522847 13,17 L13,13 C13,12.4477153 12.5522847,12 12,12 Z M6.96472382,12.1362967 C6.43125772,12.2792385 6.11467523,12.8275755 6.25761704,13.3610416 L7.29289322,17.2247449 C7.43583503,17.758211 7.98417199,18.0747935 8.51763809,17.9318517 C9.05110419,17.7889098 9.36768668,17.2405729 9.22474487,16.7071068 L8.18946869,12.8434035 C8.04652688,12.3099374 7.49818992,11.9933549 6.96472382,12.1362967 Z M17.0352762,12.1362967 C16.5018101,11.9933549 15.9534731,12.3099374 15.8105313,12.8434035 L14.7752551,16.7071068 C14.6323133,17.2405729 14.9488958,17.7889098 15.4823619,17.9318517 C16.015828,18.0747935 16.564165,17.758211 16.7071068,17.2247449 L17.742383,13.3610416 C17.8853248,12.8275755 17.5687423,12.2792385 17.0352762,12.1362967 Z"></path>
                                </g>
                            </svg>
                        </span>

                        <h3 class="fs-5 mt-4">Monthly Orders</h3>
                        <p>Last 30 days</p>

                        <!-- chart -->
                        <div class="position-relative" style="height:100px">
                            <canvas class="chartjs"
                                    data-chartjs-dots="false"
                                    data-chartjs-legend="false"
                                    data-chartjs-grid="false"
                                    data-chartjs-tooltip="true"

                                    data-chartjs-line-width="3"
                                    data-chartjs-type="line"

                                    data-chartjs-labels='["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]'
                                    data-chartjs-datasets='[{
                        "label": "Orders",
                        "data": [11, 11, 17, 11, 15, 12, 13, 16, 11, 18, 20, 21],
                        "fill": false,
                        "backgroundColor": "rgba(153, 102, 255, 1)"
                    }]'></canvas>
                        </div>

                    </div>
                    <!-- /small graph widget -->

                </div>

            </div>
            <!-- /widgets -->


            <!-- widgets -->
            <div class="row g-3">

                <!-- tasks -->
                <div class="col-12 col-xl-4">

                    <div class="portlet">

                        <div class="portlet-header">

                            <ul class="nav nav-sm nav-invert rounded-pill float-end " id="tabUserList">
                                <li class="nav-item">
                                    <a class="nav-link py-1 px-2 active" id="usertab-1" data-bs-toggle="tab" href="#usertab_1">
                                        Today
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link py-1 px-2" id="usertab-2" data-bs-toggle="tab" href="#usertab_2">
                                        Month
                                    </a>
                                </li>
                            </ul>

                            <span class="d-block text-muted text-truncate fw-medium">
                                Tasks
                            </span>

                        </div>



                    </div>

                </div>
                <!-- /tasks -->


                <!-- tickets -->
                <div class="col-12 col-xl-4">

                    <div class="portlet">

                        <div class="portlet-header">

                            <div class="float-end dropdown">

                                <!-- reload ajax content -->
                                <a href="#" id="summaryTicketListReloadBtn" aria-label="Reload Content" class="btn btn-sm btn-light px-2 py-1 fs-6 mt-n1">
                                    <span class="group-icon">
                                        <i class="fi fi-circle-spin"></i>
                                        <i class="fi fi-circle-spin fi-spin"></i>
                                    </span>
                                </a>

                                <!-- dropdown -->
                                <button type="button" class="dropdown-toggle btn btn-sm btn-light px-2 py-1 mt-n1" id="dropdownRecent" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="options">
                                    <span>
                                        <svg width="18px" height="18px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                                        </svg>
                                    </span>
                                </button>

                                <!--

                                  Note: .dropdown-menu-click-update
                                    is the key class to set the link active on click

                                -->
                                <div class="prefix-link-icon prefix-icon-dot dropdown-menu dropdown-menu-invert dropdown-menu-click-update end-0 mt-2" aria-labelledby="dropdownRecent">

                                    <div class="dropdown-header smaller px-4 text-muted fw-medium">
                                        Filter Tickets
                                    </div>

                                    <!--

                                      Note: these are working ajax links!
                                      html_frontend/documentation/plugins-sow-ajax-navigation.html
                                      .js-ajax class required

                                      Both plugins are working together:
                                        SOW : Ajax Navigation
                                        SOW : Ajax Content

                                    -->
                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_tickets_recent.html"
                                       data-ajax-target-container="#summaryTicketList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        Recent
                                    </a>

                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_tickets_open.html"
                                       data-ajax-target-container="#summaryTicketList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        Open
                                    </a>

                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_tickets_closed.html"
                                       data-ajax-target-container="#summaryTicketList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        Cosed
                                    </a>

                                </div>
                                <!-- /dropdown -->

                            </div>

                            <span class="d-block text-muted text-truncate fw-medium">
                                Support Tickets
                            </span>

                        </div>


                        <!-- content loaded via ajax (on page load)! -->
                        <div id="summaryTicketList"
                             data-ajax-url="_ajax/widget_tickets_recent.html"
                             data-ajax-btn-reload="#summaryTicketListReloadBtn"
                             class="js-ajax portlet-body scrollable-vertical scrollable-styled-dark" style="max-height:500px">

                            <!-- ajax content pushed here -->
                            <div class="text-center p-5"><!-- loader indicator -->
                                <i class="fi fi-circle-spin fi-spin fs-1 text-gray-300"></i>
                            </div>

                        </div>

                        <div class="d-flex align-self-baseline w-100 py-2">
                            <a href="#" class="btn btn-sm link-muted border-0 shadow-none">
                                <i class="fi fi-arrow-end"></i>
                                <span>View All</span>
                            </a>
                        </div>

                    </div>

                </div>
                <!-- /tickets -->


                <!-- logs -->
                <div class="col-12 col-xl-4">

                    <div class="portlet">

                        <div class="portlet-header">

                            <div class="float-end dropdown">

                                <!-- reload ajax content -->
                                <a href="#" id="summaryNotificationListReloadBtn" aria-label="Reload Content" class="btn btn-sm btn-light px-2 py-1 fs-6 mt-n1">
                                    <span class="group-icon">
                                        <i class="fi fi-circle-spin"></i>
                                        <i class="fi fi-circle-spin fi-spin"></i>
                                    </span>
                                </a>

                                <!-- dropdown -->
                                <button type="button" class="dropdown-toggle btn btn-sm btn-light px-2 py-1 mt-n1" id="dropdownLog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="js-trigger-text">Recent</span>
                                    <i class="fi fi-arrow-down"></i>
                                </button>

                                <!--

                                  Note: .dropdown-menu-click-update
                                    is the key class to set the link active on click

                                -->
                                <div class="prefix-link-icon prefix-icon-dot dropdown-menu dropdown-menu-click-update dropdown-menu-invert end-0 mt-2" aria-labelledby="dropdownLog">

                                    <div class="dropdown-header smaller px-4 text-muted fw-medium">
                                        Filter Tickets
                                    </div>

                                    <!--

                                      Note: these are working ajax links!
                                      html_frontend/documentation/plugins-sow-ajax-navigation.html
                                      .js-ajax class required

                                      Both plugins are working together:
                                        SOW : Ajax Navigation
                                        SOW : Ajax Content

                                      data-href used here, instead of href to hide
                                      the link from "open in new tab" actions

                                    -->
                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_notification_recent.html"
                                       data-ajax-target-container="#summaryNotificationList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        <span class="js-trigger-text">Recent</span>
                                    </a>

                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_notification_errors.html"
                                       data-ajax-target-container="#summaryNotificationList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        <span class="js-trigger-text">Errors</span>
                                    </a>

                                    <a class="js-ajax dropdown-item"
                                       href="#"
                                       data-href="_ajax/widget_notification_warnings.html"
                                       data-ajax-target-container="#summaryNotificationList"
                                       data-ajax-update-url="false"
                                       data-ajax-show-loading-icon="true"
                                       data-ajax-autoscroll-to-content="false">
                                        <span class="js-trigger-text">Warnings</span>
                                    </a>

                                </div>
                                <!-- /dropdown -->

                            </div>

                            <span class="d-block text-muted text-truncate fw-medium">
                                Notifications
                            </span>

                        </div>


                        <!-- content loaded via ajax (on page load)! -->
                        <div id="summaryNotificationList"
                             data-ajax-url="_ajax/widget_notification_recent.html"
                             data-ajax-btn-reload="#summaryNotificationListReloadBtn"
                             class="js-ajax portlet-body scrollable-vertical scrollable-styled-dark" style="max-height:500px">

                            <!-- ajax content pushed here -->
                            <div class="text-center p-5"><!-- loader indicator -->
                                <i class="fi fi-circle-spin fi-spin fs-1 text-gray-300"></i>
                            </div>

                        </div>


                        <div class="d-flex align-self-baseline w-100 py-2">
                            <a href="#" class="btn btn-sm link-muted border-0 shadow-none">
                                <i class="fi fi-arrow-end"></i>
                                <span>View All</span>
                            </a>
                        </div>

                    </div>

                </div>
                <!-- /logs -->


                <!-- alert : upgrade -->
                <div class="col-12">
                    <div class="d-flex align-items-center bg-white shadow-xs rounded small p-3">

                        <a href="#" title="Upgrade" aria-label="Upgrade" class="btn  btn-sm btn-warning py-1 mb-0 me-3 float-start transition-hover-end">
                            Upgrade
                        </a>
                        <span class="d-block pt-1 px-2 text-muted text-truncate">
                            using 89% of total storage
                        </span>

                    </div>
                </div>
                <!-- /alert : upgrade -->


                <!-- imports -->
                <div class="col-12 col-xl-8">

                    <div class="portlet">

                        <div class="portlet-header">

                            <div class="float-end dropdown">

                                <a href="#" class="btn btn-sm btn-light px-2 py-1 mt-n1">
                                    <small>View All</small>
                                </a>

                            </div>

                            <span class="d-block text-muted text-truncate fw-medium">
                                Shop Imports
                            </span>

                        </div>


                        <!-- content loaded via ajax! -->
                        <div class="portlet-body max-h-500 scrollable-vertical scrollable-styled-dark">

                            <!-- item -->
                            <div class="d-flex align-items-center p-3 border-bottom border-light">

                                <div class="flex-none">
                                    <img width="40" height="40" class="img-fluid lazy" data-src="../html_frontend/demo.files/svg/icons/files/csv.svg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="...">
                                </div>

                                <div class="flex-fill text-truncate px-3">
                                    <span class="text-muted">384 items imported, 766 updated, 12 ignored</span>
                                    <span class="small d-block text-muted">29 June, 2020 / 18:44</span>
                                </div>

                                <div class="flex-none ms-2 small text-muted text-align-end dropdown">

                                    <!-- dropdown -->
                                    <a id="dropdownImport_1" href="#" class="dropdown-toggle btn btn-sm btn-light px-2 py-1 mt-n1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="options">
                                        <span>
                                            <svg width="18px" height="18px" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                                            </svg>
                                        </span>
                                    </a>
                                    <div aria-labelledby="dropdownImport_1" class="prefix-link-icon prefix-icon-dot dropdown-menu mt-2">
                                        <a href="#" class="dropdown-item">View Log</a>
                                        <a href="#" class="dropdown-item">Download</a>
                                    </div>
                                    <!-- /dropdown -->

                                </div>


                            </div>
                            <!-- /item -->



                            <!-- item -->
                            <div class="d-flex align-items-center p-3 border-bottom border-light">

                                <div class="flex-none">
                                    <img width="40" height="40" class="img-fluid lazy" data-src="../html_frontend/demo.files/svg/icons/files/csv.svg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="...">
                                </div>

                                <div class="flex-fill text-truncate px-3">
                                    <span class="text-muted">12 items imported, 67 updated, 1 ignored</span>
                                    <span class="small d-block text-muted">15 February, 2020 / 16:51</span>
                                </div>

                                <div class="flex-none ms-2 small text-muted text-align-end">
                                    <a href="#">view log</a>
                                </div>

                            </div>
                            <!-- /item -->



                            <!-- item -->
                            <div class="d-flex align-items-center p-3 border-bottom border-light">

                                <div class="flex-none">
                                    <img width="40" height="40" class="img-fluid lazy" data-src="../html_frontend/demo.files/svg/icons/files/jpg.svg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="...">
                                </div>

                                <div class="flex-fill text-truncate px-3">
                                    <span class="text-muted">384 images imported to 281 products</span>
                                    <span class="small d-block text-muted">1 February, 2020 / 13:43</span>
                                </div>

                                <div class="flex-none ms-2 small text-muted text-align-end">
                                    <a href="#">view log</a>
                                </div>

                            </div>
                            <!-- /item -->

                        </div>

                        <div class="d-flex align-self-baseline w-100 py-2">
                            <a href="#" class="btn btn-sm link-muted border-0 shadow-none">
                                <i class="fi fi-arrow-end"></i>
                                <span>View All</span>
                            </a>
                        </div>

                    </div>

                </div>
                <!-- /imports -->


                <!-- orders -->
                <div class="col-12 col-xl-4">

                    <div class="portlet">

                        <div class="portlet-header">

                            <!-- dropdown options -->
                            <div class="float-end dropdown">
                                <button type="button" class="dropdown-toggle btn btn-sm btn-light px-2 py-1 mt-n1" id="dropdownOrders" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="js-trigger-text small">Export</span>
                                    <i class="fi fi-arrow-down"></i>
                                </button>
                                <div class="prefix-link-icon prefix-icon-dot dropdown-menu dropdown-menu-invert mt-2" aria-labelledby="dropdownOrders">
                                    <div class="dropdown-header smaller px-4 text-muted fw-medium">Export options</div>
                                    <a href="#" class="dropdown-item">Export CSV</a>
                                    <a href="#" class="dropdown-item">Export XLS</a>
                                    <a href="#" class="dropdown-item">Export PDF</a>
                                    <a href="#" class="dropdown-item">Print</a>
                                </div>
                            </div>
                            <!-- /dropdown options -->

                            <span class="d-block text-muted text-truncate fw-medium">
                                Orders
                            </span>

                        </div>

                        <!-- items -->
                        <div class="portlet-body scrollable-vertical scrollable-styled-dark" style="max-height:300px">

                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$2155.00</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">2 items</p>
                                <small class="text-muted d-block smaller">3 hours ago</small>

                            </a>
                            <!-- /item -->


                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$280.00</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">3 items</p>
                                <small class="text-muted d-block smaller">8 hours ago</small>

                            </a>
                            <!-- /item -->


                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$999.98</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">4 items</p>
                                <small class="text-muted d-block smaller">12 hours ago</small>

                            </a>
                            <!-- /item -->


                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$596.36</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">1 items</p>
                                <small class="text-muted d-block smaller">24 hours ago</small>

                            </a>
                            <!-- /item -->


                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$280.00</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">3 items</p>
                                <small class="text-muted d-block smaller">8 hours ago</small>

                            </a>
                            <!-- /item -->


                            <!-- item -->
                            <a href="#" class="clearfix d-block dropdown-item fw-medium p-3 rounded overflow-hidden border-bottom border-light">

                                <!-- badge -->
                                <span class="float-end fw-bold text-muted small">$999.98</span>

                                <!-- icon -->
                                <div class="float-start avatar avatar-sm rounded-circle bg-gray-200 fs-5">
                                    <i class="fi fi-cart-1"></i>
                                </div>

                                <p class="m-0 text-truncate fw-normal">4 items</p>
                                <small class="text-muted d-block smaller">12 hours ago</small>

                            </a>
                            <!-- /item -->

                        </div>
                        <!-- /items -->

                        <div class="d-flex align-self-baseline w-100 py-2">
                            <a href="#" class="btn btn-sm link-muted border-0 shadow-none">
                                <i class="fi fi-arrow-end"></i>
                                <span>View All</span>
                            </a>
                        </div>

                    </div>

                </div>
                <!-- /orders -->

            </div>
            <!-- /widgets -->




@endsection

@push('js')
    <!-- JS Implementing Plugins -->
    <script src="/assets/vendor/hs-sticky-block/dist/hs-sticky-block.min.js"></script>
    <!-- JS Implementing Plugins -->
    <script src="/assets/vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js"></script>

    <script>
        (function() {
            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            document.querySelectorAll('.js-nav-scroller').forEach(item => {
                new HsNavScroller(item).init()
            })
        });
    </script>

    <!-- JS Plugins Init. -->
    <script>
        (function() {
            // INITIALIZATION OF STICKY BLOCKS
            // =======================================================
            new HSStickyBlock('.js-sticky-block', {
                targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
            })
        })()
    </script>
@endpush
