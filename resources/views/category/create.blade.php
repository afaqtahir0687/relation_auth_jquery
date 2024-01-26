@extends('layouts.master')

@section('content')

<!-- Modal -->
<div class="modal fade ajax-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="ajaxForm">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="category_id" id="category_id">
                    <div class="form-group mb-3">
                        <label for="">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Add Category" required>
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-1">
                        <label for="">Category Type</label>
                        <select name="type" id="type" class="form-control">
                            <option disabled selected>Choose Option</option>
                            <option value="electronic">Electronics</option>
                        </select>
                        <span id="typeError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Category</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-3" style="margin-top:100px">
            <a class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add_category">Add Category</a>

            <table id="category-table" class="table" style="border: 1px solid #1487d8; text-align:center">
                <thead>
                    <tr class="heading">
                        <th scope="col">ID</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Category Type</th>
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
      
      //////// Add Table Of Category ********////////

      var table = $('#category-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('category.index') }}",
          columns: [
              { data: 'id' },
              { data: 'name' },
              { data: 'type' },
              { data: 'action', name: 'action', orderable: false, searchable: false },
          ]
      });

      ///// *****Add Category ***** /////
      $('#modal-title').html('Create Category');
      $('#saveBtn').html('Save Category');
      var form = $('#ajaxForm')[0];

      $('#saveBtn').click(function (e) {
        e.preventDefault(); 
        
          $('.error-messages').html('');
          var formData = new FormData(form);
          
          $.ajax({
              url: '{{ route("category.store") }}',
              method: 'POST',
              processData: false,
              contentType: false,
              data: formData,

              success: function (response) {
                // console.log('Success:', response);
                  table.draw();

                $('#name').val('');
                $('#type').val('');
                $('#category_id').val('');
                $('.ajax-modal').modal('hide');

                if (response.success) {
                    swal("Good job!", response.success, "success");
                }
              },
              error: function (error) {
                if (error.status === 422) {
                    $('#nameError').html(error.responseJSON.errors.name);
                    $('#typeError').html(error.responseJSON.errors.type);
                } else {
                    console.error(error);
                }
              }
          });
      });
      
      //////// ********Edit Category button code ********////////
      $('body').on('click', '.editButton', function () {
          var id = $(this).data('id');

          $.ajax({
              url: '{{ url("category") }}' + '/' + id + '/edit',
              method: 'GET',
              success: function (response) {
                  $('.ajax-modal').modal('show');
                  $('#modal-title').html('Edit Category');
                  $('#saveBtn').html('Update Category');

                  $('#category_id').val(response.id);
                  $('#name').val(response.name);
                  
                  var type = capitalizeFirstLetter(response.type);
                  $('#type').empty().append('<option selected value="' + response.type + '">' + type + '</option>');
              },
              error: function (error) {
                  console.log(error);
              }
          });
      });
      
      $('body').on('click', '.swal-button--confirm', function(){
        location.reload();
      });
      /////// *******Delete Category button code*******///////
      $('body').on('click', '.delButton', function(){
        var id = $(this).data('id');

        $.ajax({
              url: '{{ url("category/delete") }}' + '/' + id,
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

      $('#add_category').click(function(){
        $('#modal-title').html('Create Category');
        $('#saveBtn').html('Save Category');
      })

      function capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
      }
  });
</script>


@endsection
