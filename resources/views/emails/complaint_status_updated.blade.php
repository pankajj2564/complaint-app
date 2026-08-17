<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Complaint Status Updated</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f9fc; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <h2 style="color: #4f46e5; margin-top: 0;">Complaint Status Update</h2>
        
        <p>Hello <strong>{{ $complaint->user->name }}</strong>,</p>
        <p>Your complaint status has been updated by the assigned employee.</p>

        <div style="background: #f8fafc; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #4f46e5;">
            <p style="margin: 5px 0;"><strong>Ticket Number:</strong> #{{ $complaint->ticket_number }}</p>
            <p style="margin: 5px 0;"><strong>Category:</strong> {{ $complaint->category->name ?? 'N/A' }}</p>
            <p style="margin: 5px 0;"><strong>New Status:</strong> <span style="color: #2563eb; text-transform: uppercase; font-weight: bold;">{{ str_replace('_', ' ', $complaint->status) }}</span></p>
            <p style="margin: 5px 0;"><strong>Assigned Employee:</strong> {{ $complaint->assignedEmployee->name ?? 'N/A' }}</p>
            <p style="margin: 5px 0;"><strong>Description:</strong> {{ $complaint->description }}</p>
        </div>

        <p style="color: #64748b; font-size: 14px; margin-top: 30px;">Thank you,<br><strong>CGC University Support Team</strong></p>
    </div>
</body>
</html>