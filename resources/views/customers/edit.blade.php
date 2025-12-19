@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Customer</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Contact No</label>
                        <input type="text" name="contact_no" class="form-control" value="{{ $customer->contact_no }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>GST No</label>
                        <input type="text" name="gst_no" class="form-control" value="{{ $customer->gst_no }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                </div>
                
                <h4>Optional Fields</h4>
                <div id="optional-fields">
                    @if($customer->optional_fields)
                        @foreach($customer->optional_fields as $key => $value)
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <input type="text" name="optional_keys[]" class="form-control" value="{{ $key }}">
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="optional_values[]" class="form-control" value="{{ $value }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">X</button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="addOptionalField()">+ Add Optional Field</button>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
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
