<!DOCTYPE html>
<html>
<head>
    <title>Complaint Ticket Generated</title>
</head>
<body>
    <h2>New Complaint Submitted</h2>
    <p>Hello,</p>
    <p>A new complaint has been registered on the portal.</p>
    
    <h3>Student Details:</h3>
    <ul>
        <li><strong>Name:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
    </ul>

    <h3>Complaint Details:</h3>
    <ul>
        <li><strong>Ticket Number:</strong> {{ $complaint->ticket_number }}</li>
        <li><strong>Location:</strong> {{ $complaint->location }}</li>
        <li><strong>Description:</strong> {{ $complaint->description }}</li>
    </ul>

    <p>You can reply directly to this email to communicate with the student.</p>
    
    <br>
    <p>Regards,<br>CGC University Mohali</p>
</body>
</html>