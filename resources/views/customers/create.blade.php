@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Customer</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Contact No</label>
                        <input type="text" name="contact_no" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>GST No</label>
                        <input type="text" name="gst_no" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                
                <h4>Optional Fields</h4>
                <div id="optional-fields"></div>
                <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="addOptionalField()">+ Add Optional Field</button>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addOptionalField() {
    let html = `
    <div class="row mb-2">
        <div class="col-md-5">
             <input type="text" name="optional_keys[]" class="form-control" placeholder="Field Name">
        </div>
        <div class="col-md-5">
             <input type="text" name="optional_values[]" class="form-control" placeholder="Value">
        </div>
        <div class="col-md-2">
             <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">X</button>
        </div>
    </div>`;
    document.getElementById('optional-fields').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
