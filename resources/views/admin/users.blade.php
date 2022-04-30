@extends('layouts.admin.app')
@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12 py-2 pb-3 d-flex justify-content-between">
        <h3>Graduates</h3>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table" style="min-width: 700px">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nmae</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gender</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Country</th>
                        <th scope="col">Screens</th>
                        <th scope="col">Images</th>
                        <th scope="col">Logins</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                    <th scope="">1</th>
                        <td>Muhammad</td>
                        <td>Muhammad@gmail.com</td>
                        <td>Male</td>
                        <td>  12<sup>th</sup>March, 2000</td>
                        <td>Pakistan</td>
                        <td>3</td>
                        <td>30</td>
                        <td>10</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addManager" tabindex="-1" role="dialog" aria-labelledby="addManagerTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addManagerTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection
