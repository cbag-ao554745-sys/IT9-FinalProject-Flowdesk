<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FlowDesk Report - {{ now()->format('F d, Y') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            color: #1e40af;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
        }
        .kpi {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .kpi-box {
            border: 1px solid #ddd;
            padding: 12px 20px;
            border-radius: 8px;
            width: 23%;
            text-align: center;
        }
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #4f46e5;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>FlowDesk - Reports & Analytics</h1>
        <p>Generated on: {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>

    <!-- KPI Summary -->
    <h2>Summary</h2>
    <div class="kpi">
        <div class="kpi-box">
            <strong>Total Tasks</strong><br>
            <span style="font-size: 28px; color: #1e3a8a;">{{ $summary['total_tasks'] ?? 0 }}</span>
        </div>
        <div class="kpi-box">
            <strong>Completed</strong><br>
            <span style="font-size: 28px; color: #166534;">{{ $summary['completed_tasks'] ?? 0 }}</span>
        </div>
        <div class="kpi-box">
            <strong>Pending</strong><br>
            <span style="font-size: 28px; color: #b45309;">{{ $summary['pending_tasks'] ?? 0 }}</span>
        </div>
        <div class="kpi-box">
            <strong>Overdue</strong><br>
            <span style="font-size: 28px; color: #b91c1c;">{{ $summary['overdue_tasks'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Task Status Breakdown -->
    <h2>Task Status Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['pending', 'in_progress', 'on_hold', 'completed', 'cancelled'] as $status)
                @php
                    $count = $completionReport->get($status, 0);
                    $total = $completionReport->sum();
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                @endphp
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $status)) }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ $pct }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Project Progress -->
    <h2>Project Progress</h2>
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Progress</th>
                <th>Completed / Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projectProgress as $item)
            <tr>
                <td>{{ $item['project']->name }}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $item['percent'] }}%"></div>
                    </div>
                </td>
                <td>{{ $item['completed'] }} / {{ $item['total'] }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No active projects.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Overdue Tasks -->
    @if($overdueTasks->count())
    <h2>Overdue Tasks ({{ $overdueTasks->count() }})</h2>
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Project</th>
                <th>Assignee</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overdueTasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->project->name ?? '—' }}</td>
                <td>{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                <td>{{ $task->due_date->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <p style="text-align: center; margin-top: 40px; color: #666; font-size: 12px;">
        FlowDesk Task Management System • Generated Report
    </p>

</body>
</html>