@extends('layouts.app')

@section('content')

<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-shopping-cart fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Order</p>
                    <h6 class="mb-0">$1234</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-user fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Customer</p>
                    <h6 class="mb-0">$1234</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-wrench fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Service</p>
                    <h6 class="mb-0">$1234</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-wallet fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Money balance</p>
                    <h6 class="mb-0">$1234</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Latest service</h6>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">No</th>
                        <th scope="col">Date</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>01 Jan 2045</td>
                        <td>INV-0123</td>
                        <td>John Doe</td>
                        <td>$123</td>
                        <td>Paid</td>
                        <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>02 Jan 2045</td>
                        <td>INV-0456</td>
                        <td>Jane Smith</td>
                        <td>$321</td>
                        <td>Pending</td>
                        <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>03 Jan 2045</td>
                        <td>INV-0789</td>
                        <td>Michael Lee</td>
                        <td>$456</td>
                        <td>Paid</td>
                        <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>04 Jan 2045</td>
                        <td>INV-0999</td>
                        <td>Emily Clark</td>
                        <td>$654</td>
                        <td>Cancelled</td>
                        <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>05 Jan 2045</td>
                        <td>INV-1001</td>
                        <td>David Park</td>
                        <td>$987</td>
                        <td>Paid</td>
                        <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Sales End -->

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
@endsection
