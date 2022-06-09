@extends('layouts.admin.app')
@section('title')
    Subscriptions
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-2 pb-3 pr-4 d-flex justify-content-between">
                <div class="">
                    <h3>Subscriptions</h3>
                </div>
                <div>
                    <a href="{{ route('admin.add-subscription') }}" class="btn btn-success" >
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-12 overflow-auto shadow p-3 mb-5 bg-white rounded">
                <table class="table" style="min-width: 700px">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Subscription Nmae</th>
                            <th scope="col">Subscription Amount</th>
                            <th scope="col">Subscription Duration</th>
                            <th scope="col" style="min-width: 160px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="companies_list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        async function getSubscriptions() {
            var url = '{{ env('APP_URL') }}' + '/admin/get-subscriptions';
            const response = await fetch(url);
            data = await response.json();
            fetch(url)
                .then(res => res.json())
                .then((dta) => {
                    let element = document.querySelector("#companies_list");
                    element.innerHTML = "";
                    console.log('data', data);
                    for (const i of data) {
                        element.innerHTML +=
                            '<tr>' +
                            '<td >' + i.name + '</td>' +
                            '<td >' + i.amount + '</td>' +
                            '<td >' + i.duration +' '+ i.type + '</td>' +
                            '<td>' +
                            '<button onclick="edit(' + i.id +
                            ')" class="btn btn-sm btn-danger fa fa-trash mr-2"></button>' +
                            '<a href="/admin/edit-subscription/'+ i.id + '"'+
                            'class="btn btn-sm btn-primary fa fa-edit"></a>' +
                            '</td>' +
                            '</tr>'
                    }

                })

        }
        getSubscriptions();



        function change() {
            $verified = document.querySelector('#verified');
            $status = document.querySelector('#status');
        }

        async function edit(id) {
            var url = '{{ env('APP_URL') }}' + '/admin/del-subscription/' + id;
            console.log(url);
            fetch(url)
                .then((res) => {
                    if (res.status === 200) {
                        toastr.success(" Subscription Successfully Deleted")
                        getSubscriptions();
                    }
                })
        }
    </script>
@endpush
