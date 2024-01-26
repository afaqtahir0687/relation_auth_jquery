@extends('layouts.master')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal -->
<div class="modal fade ajax-modal" id="productModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="ajaxForm">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Product Description</label>
                        <input type="text" name="description" id="description" class="form-control" required>
                        <span id="descriptionError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Product Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                        <span id="amountError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="category_id">Category Name</label>
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach($products as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span id="categoryError" class="text-danger error-messages"></span>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Product</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-10 offset-1" style="margin-top:100px">
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#productModal" id="add_product">Add Product</a>

            <table id="product-table" class="table" style="border: 1px solid #1487d8; text-align:center">
                <thead>
                    <tr class="heading">
                        <th scope="col">ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Description</th>
                        <th scope="col">Product Amount</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


      var table = $('#product-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('products.index') }}",
          columns: [
              { data: 'id' },
              { data: 'name' },
              { data: 'description' },
              { data: 'amount' },
              { data: 'category_name' },
              { data: 'action', name: 'action', orderable: false, searchable: false },
          ]
      });

      $('#modal-title').html('Create Product');
      $('#saveBtn').html('Save Product');
      var form = $('#ajaxForm')[0];

      $('#saveBtn').click(function () {
          $('.error-messages').html('');

          var formData = new FormData(form);
          
          $.ajax({
              url: '{{ route("products.store") }}',
              method: 'POST',
              processData: false,
              contentType: false,
              data: formData,

              success: function (response) {
                  table.draw();

                  $('#name').val('');
                  $('#description').val('');
                  $('#amount').val('');
                  $('#category_name').val('');
                  $('.ajax-modal').modal('hide');
                  if (response.success) {
                      swal("Good job!", response.success, "success");
                  }
                },
                error: function (error) {
                    if (error.status === 422) {
                        $('#nameError').html(error.responseJSON.errors.name);
                        $('#descriptionError').html(error.responseJSON.errors.description);
                        $('#amountError').html(error.responseJSON.errors.amount);
                        $('#categoryError').html(error.responseJSON.errors.category_id);
                    } else {
                      console.error(error);
                  }
              }
          });
      });


    // Edit button code
    $('body').on('click', '.editButton', function () {
          var id = $(this).data('id');

          $.ajax({
              url: '{{ url("products") }}' + '/' + id + '/edit',
              method: 'GET',
              success: function (response) {
                  $('.ajax-modal').modal('show');
                  $('#modal-title').html('Edit Product');
                  $('#saveBtn').html('Update Product');

                  $('#product_id').val(response.id);
                  $('#name').val(response.name);
                  $('#description').val(response.description);
                  $('#amount').val(response.amount);
                  $('#category_id').val(response.category_id);
              },
              error: function (error) {
                  console.log(error);
              }
          });
      });

      $('body').on('click', '.swal-button--confirm', function(){
        location.reload();
      });
    // Delete Product
    $('body').on('click', '.delButton', function(){
        var id = $(this).data('id');

        $.ajax({
            url: '{{ url("products/delete") }}' + '/' + id,
            method: 'DELETE',
            success: function (response) {
                table.draw();
                swal("Delete!", response.success, "success");
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#add_product').click(function(){
        $('#modal-title').html('Create Product');
        $('#saveBtn').html('Save Product');
    });

});
</script>

@endsection
