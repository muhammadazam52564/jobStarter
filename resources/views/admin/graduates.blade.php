@extends('layouts.admin.app')
@section('title')
    Graduates
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-2 pb-3 d-flex justify-content-between">
            <div class="w-50">
                <h3>Graduates</h3>
            </div>
            {{-- <div class="w-50 d-flex">
                <div class="w-50 px-4">
                    <label for="vrtified">Email verified</label>
                    <select class="form-control"  id="verified" onchange="change()">
                        <option value="">--All--</option>
                        <option value="0"> unverified </option>
                        <option value="1"> verified </option>
                    </select>
                </div>
                <div class="w-50 px-4">
                    <label for="vrtified">Account Status</label>
                    <select class="form-control" id="status" onchange="change()">
                        <option value=""> --All-- </option>
                        <option value="0">Pending Approval </option>
                        <option value="1"> Approved </option>
                        <option value="2"> Blocked </option>

                    </select>
                </div>
            </div> --}}
        </div>
        <div class="col-md-12 overflow-auto shadow p-3 mb-5 bg-white rounded">
            <table id="graduates_table" class="table" style="min-width: 700px">
                <thead class="thead-light">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Nmae</th>
                        <th scope="col">Email</th>
                        <th scope="col">Address</th>
                        <th scope="col">Age</th>
                        <th scope="col">School</th>
                        <th scope="col">Description</th>
                        <th scope="col" style="min-width: 140px">Email verified</th>
                        <th scope="col" style="min-width: 140px">Account Status</th>
                        <th scope="col" style="min-width: 140px">Action</th>
                    </tr>
                </thead>
                <tbody id="graduates_list"></tbody>
            </table>
        </div>
    </div>
</div>


@endsection
@push('scripts')
    <script>
        async function getGraduates(){
            var url = '{{ env('APP_URL') }}' + '/admin/get-graduates';
            fetch(url, data={

            })
            .then(res => res.json())
            .then((data)=>{
                let element = document.querySelector("#graduates_list");
                element.innerHTML = "";
                for (const i of data)
                {
                    let age= "";
                    if (i.dob) {
                        age =  new Date().getFullYear() - new Date(Date.parse(i.dob)).getFullYear();
                    }
                    let status = i.status == 0?
                        '<div class="btn btn-warning btn-sm py-0 px-3"> Pending </div>'
                        :
                        i.status == 1?
                            '<div class="btn btn-success btn-sm py-0 px-3"> Active </div>'
                            :
                            '<div class="btn btn-danger btn-sm py-0 px-3"> Blocked </div>'

                    let btn = i.status == 0?
                            '<button onclick="update_status('+i.id+', 1)" class="btn btn-sm btn-success fal fa-check-circle ml-2" title="Approve Graduate"> Approve</button>'
                        :
                            i.status == 1?
                                '<button onclick="update_status('+i.id+', 2)"  class="btn btn-sm btn-danger fa fa-ban ml-2" title="Block Graduate"> Block</button>'
                            :
                                '<button onclick="update_status('+i.id+', 1)"  class="btn btn-sm btn-success fa fa-ban ml-2" title="Unblock Graduate"> Unblock</button>'

                    let verified = i.verified == 1 ?
                        '<div class="btn btn-success btn-sm py-0 px-3"> verified </div>'
                    :
                        '<div class="btn btn-warning btn-sm py-0 px-3"> unverified </div>'


                    element.innerHTML +=
                        '<tr>' +
                            '<td >' +
                                '<img src="../'+i.profile_image+ '"  class="rounded-circle" width="35px" height="35px"/>' +
                            '</td>' +
                            '<td >'+i.name+'</td>' +
                            '<td>'+i.email+'</td>' +
                            '<td>'+i.address+'</td>' +
                            '<td>'+ age +' years </td>' +
                            '<td>'+i.school+'</td>' +
                            '<td>'+i.description+'</td>' +
                            '<td>' +
                                verified +
                            '</td>' +
                            '<td>' +
                                status +
                            '</td>' +
                            '<td>'+
                                '<button onclick="deleteEntry('+i.id+')" class="btn btn-sm btn-danger fa fa-trash" title="Remove Graduate"></button>' +
                                btn +
                            '</td>' +
                        '</tr>'
                }

            })

        }getGraduates();

        function change(){
            $verified   = document.querySelector('#verified');
            $status     = document.querySelector('#status');
        }

        async function update_status(id, status) {
            var url = '{{ env('APP_URL') }}' + '/admin/update-status';
            const csrfToken = document.head.querySelector("[name=_token][content]").content;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": csrfToken,
                },
                body: JSON.stringify({
                    id:id,
                    status:status
                }),
            }).then( (res)=>{
                if (res.status === 200) {
                    toastr.success("Graduate Successfully Updated")
                    getGraduates();
                }
            })
        }

        async function deleteEntry(id) {
            var url = '{{ env('APP_URL') }}' + '/admin/del-graduate/' + id;
            console.log(url);
            fetch(url)
            .then((res)=>{
                if (res.status === 200) {
                    toastr.success("Graduate Successfully Deleted")
                    getGraduates();
                }
            })
        }

    </script>
@endpush
