<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hardware Recommendation - {{ $ticket->ticket_no }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #4f46e5; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .info-section { margin-bottom: 30px; }
        .info-grid { width: 100%; border-collapse: collapse; }
        .info-grid td { padding: 8px 0; font-size: 14px; }
        .label { font-weight: bold; color: #666; width: 150px; }
        .specs-container { background: #f9fafb; border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; }
        .specs-title { font-weight: bold; font-size: 16px; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .specs-content { font-family: 'Courier', monospace; white-space: pre-wrap; font-size: 14px; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>IT HARDWARE RECOMMENDATION</h1>
        <p>Ticketing IT System (TITS)</p>
    </div>

    <div class="info-section">
        <table class="info-grid">
            <tr>
                <td class="label">Ticket Number:</td>
                <td><strong>{{ $ticket->ticket_no }}</strong></td>
                <td class="label">Date Generated:</td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td class="label">Requested By:</td>
                <td>{{ $ticket->user->username }}</td>
                <td class="label">Department:</td>
                <td>{{ $ticket->user->department }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <div class="label" style="margin-bottom: 10px;">User Requirement:</div>
        <p style="font-style: italic; font-size: 13px; margin: 0;">"{{ $ticket->description }}"</p>
    </div>

    <div class="specs-container">
        <div class="specs-title">Recommended Specifications</div>
        <div class="specs-content">{{ $specs }}</div>
    </div>

    <div class="footer">
        <p>This is a system-generated document from the TITS Platform.</p>
        <p>© {{ date('Y') }} IT Department</p>
    </div>
</body>
</html>
