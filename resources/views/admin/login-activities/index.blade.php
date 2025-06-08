@extends('layouts.admin')

@section('title', 'User Login Activities')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Login Activity Log</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>IP Address</th>
                            <th>Device / Browser</th>
                            <th>Platform</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            <tr>
                                <td>{{ $activity->id }}</td>
                                <td>{{ $activity->user->name }}</td>
                                <td>{{ $activity->ip_address }}</td>
                                <td>{{ $activity->device }} / {{ $activity->browser }}</td>
                                <td>{{ $activity->platform }}</td>
                                <td>{{ $activity->login_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $activity->logout_at ? $activity->logout_at->format('Y-m-d H:i:s') : 'Still active or no logout recorded' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No login activities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Add any JavaScript for enhancements
        });
    </script>
@stop
