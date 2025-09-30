<!DOCTYPE html>
<html>
<head>
    <title>Test Friends System</title>
</head>
<body>
    <h1>Friends System Test</h1>
    
    @auth
        <h2>Current User: {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})</h2>
        
        <div>
            <h3>Friends Count: {{ auth()->user()->friends()->count() }}</h3>
        </div>
        
        <div>
            <h3>Sent Friend Requests: {{ auth()->user()->sentFriendRequests()->count() }}</h3>
        </div>
        
        <div>
            <h3>Received Friend Requests: {{ auth()->user()->receivedFriendRequests()->count() }}</h3>
        </div>
        
        <div>
            <h3>Pending Friend Requests: {{ auth()->user()->pendingFriendRequests()->count() }}</h3>
        </div>
        
        <div>
            <h3>Sent Pending Requests: {{ auth()->user()->sentPendingRequests()->count() }}</h3>
        </div>
    @else
        <p>Please login to test the friends system.</p>
    @endauth
</body>
</html>