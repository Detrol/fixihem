@extends('layout.app')

@section('content')
    <main id="content" role="main">
        <!-- Hero -->
        <div class="container content-space-t-3">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Admin Login</div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form action="{{ route('admin.login') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection

@push('js')
@endpush
